<?php

namespace NftPortfolioTracker\Handler\Account;

use NftPortfolioTracker\Entity\Project;
use NftPortfolioTracker\Enum\TransactionDirectionEnum;
use NftPortfolioTracker\Etherscan\Client;
use NftPortfolioTracker\Etherscan\Exception\EtherscanApiRequestFailed;
use NftPortfolioTracker\Event\Account\AccountAddedEvent;
use NftPortfolioTracker\Event\Account\AccountAssetsChangedEvent;
use NftPortfolioTracker\Event\Account\AccountBalanceChangedEvent;
use NftPortfolioTracker\Event\Account\AccountEvent;
use NftPortfolioTracker\Event\Account\AccountUpdatedEvent;
use NftPortfolioTracker\Event\Transaction\TransactionInEvent;
use NftPortfolioTracker\Event\Transaction\TransactionOutEvent;
use NftPortfolioTracker\Repository\AccountTransactionRepository;
use NftPortfolioTracker\Repository\ProjectRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractAccountEventHandler
{
    protected LoggerInterface $logger;
    protected Client $etherscanClient;
    protected EventDispatcherInterface $eventDispatcher;
    protected EventDispatcherInterface $asyncEventDispatcher;
    protected AccountTransactionRepository $transactionRepository;

    public function __construct(LoggerInterface $logger, Client $etherscanClient, EventDispatcherInterface $eventDispatcher, EventDispatcherInterface $asyncEventDispatcher, AccountTransactionRepository $transactionRepository)
    {
        $this->logger = $logger;
        $this->etherscanClient = $etherscanClient;
        $this->eventDispatcher = $eventDispatcher;
        $this->asyncEventDispatcher = $asyncEventDispatcher;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param Project[] $projects
     */
    public function handleAccountEvent(AccountEvent $event, array $projects): void
    {
        $transactionHashes = [];

        foreach ($projects as $project) {
            $latestBlockNumber = null;

            if ($event instanceof AccountAddedEvent) {
                $latestBlockNumber = $this->transactionRepository->getLatestBlockNumberForAccount($event->getAddress(), $project->getEtherscanUrl());
            }

            if ($event instanceof AccountUpdatedEvent && count($event->getContracts()) !== 1 && $this->transactionRepository->hasTransactionsForProject($project)) { // when event was not triggered by new project/contract
                $latestBlockNumber = $this->transactionRepository->getLatestBlockNumberForAccount($event->getAddress(), $project->getEtherscanUrl());
            }

            $transactions = $this->etherscanClient->getErc721Transactions($event->getAddress(), [$project], $latestBlockNumber);

            foreach ($transactions as $transaction) {
                if (!isset($transaction['hash'])) {
                    $this->logger->warning('Empty transaction', [$event->getAddress(), $latestBlockNumber]);
                    continue;
                }

                if ($transaction['blockNumber'] == $latestBlockNumber && $this->transactionRepository->findOneBy(['transactionHash' => $transaction['hash']]) !== null) {
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

                /*if ($transaction['tokenID'] == '3410') {
                    dump($transaction);
                    dump($transactionOutgoing);
                    dump($transactionIncoming);
                    dump($transactionAddedEvent);
                    exit;
                }*/

                $this->eventDispatcher->dispatch($transactionAddedEvent);
                $transactionHashes[] = $transaction['hash'];
            }
        }

        foreach ([TransactionDirectionEnum::OUT, TransactionDirectionEnum::IN] as $direction) {
            $openSeaTransactions = $this->etherscanClient->getTransactionsForOpenSea($event->getAddress(), $latestBlockNumber, $direction);

            foreach ($openSeaTransactions as $openSeaTransaction) {
                if (!array_key_exists('hash', $openSeaTransaction)) {
                    continue;
                }

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
            }
        }

        if (count($transactionHashes) !== 0) {
            $this->asyncEventDispatcher->dispatch(AccountAssetsChangedEvent::create($event->getAddress()));
            $this->asyncEventDispatcher->dispatch(AccountBalanceChangedEvent::create($event->getAddress()));
        }
    }
}
