<?php

namespace NftPortfolioTracker\Handler\Account;

use NftPortfolioTracker\Enum\TransactionDirectionEnum;
use NftPortfolioTracker\Etherscan\Client;
use NftPortfolioTracker\Etherscan\Exception\EtherscanApiRequestFailed;
use NftPortfolioTracker\Event\Account\AccountAddedEvent;
use NftPortfolioTracker\Event\Account\AccountAssetsChangedEvent;
use NftPortfolioTracker\Event\Account\AccountBalanceChangedEvent;
use NftPortfolioTracker\Event\Transaction\TransactionInEvent;
use NftPortfolioTracker\Event\Transaction\TransactionOutEvent;
use NftPortfolioTracker\Repository\AccountTransactionRepository;
use NftPortfolioTracker\Repository\ProjectRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountAddedEventHandler implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private ProjectRepository $projectRepository;
    private AccountTransactionRepository $transactionRepository;
    private Client $etherscanClient;
    private EventDispatcherInterface $eventDispatcher;
    private EventDispatcherInterface $asyncEventDispatcher;

    public function __construct(LoggerInterface $logger, ProjectRepository $projectRepository, AccountTransactionRepository $transactionRepository, Client $etherscanClient, EventDispatcherInterface $eventDispatcher, EventDispatcherInterface $asyncEventDispatcher)
    {
        $this->logger = $logger;
        $this->projectRepository = $projectRepository;
        $this->transactionRepository = $transactionRepository;
        $this->etherscanClient = $etherscanClient;
        $this->eventDispatcher = $eventDispatcher;
        $this->asyncEventDispatcher = $asyncEventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AccountAddedEvent::class => 'handle'
        ];
    }

    public function handle(AccountAddedEvent $event): void
    {
        $projects = $this->projectRepository->findAll();

        if (count($projects) === 0) {
            return;
        }

        $latestBlockNumber = $this->transactionRepository->getLatestBlockNumberForAccount($event->getAddress());

        $transactionHashes = [];
        $transactions = $this->etherscanClient->getErc721Transactions($event->getAddress(), $projects, $latestBlockNumber);

        foreach ($transactions as $transaction) {
            if (!isset($transaction['hash'])) {
                $this->logger->warning('Empty transaction', [$event->getAddress(), $latestBlockNumber]);
                continue;
            }

            $this->logger->info('Processing transaction: ' . var_export($transaction, true));

            $transactionAddedEvent = null;
            $transactionIncoming = $transaction['to'] === $event->getAddress();
            $transactionOutgoing = $transaction['from'] === $event->getAddress();

            try {
                $transaction['value'] = $this->etherscanClient->getValueForTransaction($transaction['apiUrl'], $event->getAddress(), $transaction['hash'], $transaction['blockNumber'], $transactionOutgoing);
            } catch (EtherscanApiRequestFailed $etherscanApiRequestFailed) {
                $this->logger->warning('No value for transaction found', $transaction);
                $transaction['value'] = 0;
            }

            if ($transactionOutgoing) {
                $transactionAddedEvent = TransactionOutEvent::createFromArrayWithAccount(
                    $event->getAddress(),
                    $transaction
                );
            }

            if ($transactionIncoming) {
                $transactionAddedEvent = TransactionInEvent::createFromArrayWithAccount(
                    $event->getAddress(),
                    $transaction
                );
            }

            if ($transactionAddedEvent === null) {
                $this->logger->warning('Mismatching transaction', ['account' => $event->getAddress(), 'from' => $transaction['from'], 'to' => $transaction['to']]);

                continue;
            }

            $this->eventDispatcher->dispatch($transactionAddedEvent);
            $transactionHashes[] = $transaction['hash'];
        }

        foreach ([TransactionDirectionEnum::OUT, TransactionDirectionEnum::IN] as $direction) {
            $openSeaTransactions = $this->etherscanClient->getTransactionsForOpenSea($event->getAddress(), $latestBlockNumber, $direction);

            foreach ($openSeaTransactions as $openSeaTransaction) {
                if (in_array($openSeaTransaction['hash'], $transactionHashes, true)) {
                    continue;
                }

                if ($direction === TransactionDirectionEnum::OUT) {
                    $transactionAddedEvent = TransactionOutEvent::createFromArrayWithAccount(
                        $event->getAddress(),
                        $openSeaTransaction
                    );
                }

                if ($direction === TransactionDirectionEnum::IN) {
                    $transactionAddedEvent = TransactionInEvent::createFromArrayWithAccount(
                        $event->getAddress(),
                        $openSeaTransaction
                    );
                }

                $this->eventDispatcher->dispatch($transactionAddedEvent);

                $transactionHashes[] = $openSeaTransaction['hash'];
            }
        }

        if (count($transactionHashes) !== 0) {
            $this->asyncEventDispatcher->dispatch(AccountAssetsChangedEvent::create($event->getAddress()));
            $this->asyncEventDispatcher->dispatch(AccountBalanceChangedEvent::create($event->getAddress()));
        }
    }
}