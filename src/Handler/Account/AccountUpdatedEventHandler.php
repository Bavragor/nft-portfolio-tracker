<?php

namespace NftPortfolioTracker\Handler\Account;

use NftPortfolioTracker\Etherscan\Client;
use NftPortfolioTracker\Event\Account\AccountAddedEvent;
use NftPortfolioTracker\Event\Account\AccountAssetsChanged;
use NftPortfolioTracker\Event\Account\AccountBalanceChangedEvent;
use NftPortfolioTracker\Event\Account\AccountUpdatedEvent;
use NftPortfolioTracker\Event\Transaction\TransactionInEvent;
use NftPortfolioTracker\Event\Transaction\TransactionOutEvent;
use NftPortfolioTracker\Repository\ProjectRepository;
use NftPortfolioTracker\Repository\AccountTransactionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountUpdatedEventHandler implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private AccountTransactionRepository $transactionRepository;
    private ProjectRepository $projectRepository;
    private Client $etherscanClient;
    private EventDispatcherInterface $eventDispatcher;
    private EventDispatcherInterface $asyncEventDispatcher;

    public function __construct(LoggerInterface $logger, AccountTransactionRepository $transactionRepository, ProjectRepository $projectRepository, Client $etherscanClient, EventDispatcherInterface $eventDispatcher, EventDispatcherInterface $asyncEventDispatcher)
    {
        $this->logger = $logger;
        $this->transactionRepository = $transactionRepository;
        $this->projectRepository = $projectRepository;
        $this->etherscanClient = $etherscanClient;
        $this->eventDispatcher = $eventDispatcher;
        $this->asyncEventDispatcher = $asyncEventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AccountUpdatedEvent::class => 'handle'
        ];
    }

    public function handle(AccountUpdatedEvent $event): void
    {
        $latestBlockNumber = null;

        $contracts = $event->getContracts();

        if (count($event->getContracts()) !== 1) { // when event was not triggered by new project/contract
            $contracts = $this->projectRepository->getDistinctContracts();
            $latestBlockNumber = $this->transactionRepository->getLatestBlockNumberForAccount($event->getAddress());
        }

        $this->logger->info('Transactions for contracts: ' . var_export($contracts, true));

        $transactionsCount = 0;

        $transactions = $this->etherscanClient->getErc721Transactions($event->getAddress(), $contracts, $latestBlockNumber);

        foreach ($transactions as $transaction) {
            if (!isset($transaction['hash'])) {
                $this->logger->warning('Empty transaction', [$event->getAddress(), $contracts, $latestBlockNumber]);
                continue;
            }

            $this->logger->info('Processing transaction: ' . var_export($transaction, true));
            $transactionAddedEvent = null;
            $transactionIncoming = $transaction['to'] === $event->getAddress();
            $transactionOutgoing = $transaction['from'] === $event->getAddress();

            $transaction['value'] = $this->etherscanClient->getValueForTransaction($event->getAddress(), $transaction['hash'], $transaction['blockNumber'], $transactionOutgoing);

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
            $transactionsCount++;
        }

        if ($transactionsCount !== 0) {
            $this->asyncEventDispatcher->dispatch(AccountAssetsChanged::create($event->getAddress()));
            $this->asyncEventDispatcher->dispatch(AccountBalanceChangedEvent::create($event->getAddress()));
        }
    }
}