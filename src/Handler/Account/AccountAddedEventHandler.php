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

class AccountAddedEventHandler extends AbstractAccountEventHandler implements EventSubscriberInterface
{
    private ProjectRepository $projectRepository;
    private AccountTransactionRepository $transactionRepository;

    public function __construct(
        LoggerInterface $logger,
        Client $etherscanClient,
        EventDispatcherInterface $eventDispatcher,
        EventDispatcherInterface $asyncEventDispatcher,
        ProjectRepository $projectRepository,
        AccountTransactionRepository $transactionRepository
    ) {
        parent::__construct($logger, $etherscanClient, $eventDispatcher, $asyncEventDispatcher);

        $this->projectRepository = $projectRepository;
        $this->transactionRepository = $transactionRepository;
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

        $this->handleAccountEvent($event, $projects, $latestBlockNumber);
    }
}
