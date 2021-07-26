<?php

namespace NftPortfolioTracker\Handler\Account;

use NftPortfolioTracker\Enum\TransactionDirectionEnum;
use NftPortfolioTracker\Etherscan\Client;
use NftPortfolioTracker\Etherscan\Exception\EtherscanApiRequestFailed;
use NftPortfolioTracker\Event\Account\AccountAddedEvent;
use NftPortfolioTracker\Event\Account\AccountAssetsChangedEvent;
use NftPortfolioTracker\Event\Account\AccountBalanceChangedEvent;
use NftPortfolioTracker\Event\Account\AccountUpdatedEvent;
use NftPortfolioTracker\Event\Transaction\TransactionInEvent;
use NftPortfolioTracker\Event\Transaction\TransactionOutEvent;
use NftPortfolioTracker\Repository\AccountTransactionRepository;
use NftPortfolioTracker\Repository\ProjectRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountUpdatedEventHandler extends AbstractAccountEventHandler implements EventSubscriberInterface
{
    private ProjectRepository $projectRepository;

    public function __construct(
        LoggerInterface $logger,
        Client $etherscanClient,
        EventDispatcherInterface $eventDispatcher,
        EventDispatcherInterface $asyncEventDispatcher,
        AccountTransactionRepository $accountTransactionRepository,
        ProjectRepository $projectRepository
    ) {
        parent::__construct($logger, $etherscanClient, $eventDispatcher, $asyncEventDispatcher, $accountTransactionRepository);

        $this->projectRepository = $projectRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AccountUpdatedEvent::class => 'handle'
        ];
    }

    public function handle(AccountUpdatedEvent $event): void
    {
        $contracts = $this->projectRepository->findBy(['contract' => $event->getContracts()]);

        if (count($event->getContracts()) !== 1) { // when event was not triggered by new project/contract
            $contracts = $this->projectRepository->findAll();
        }

        $this->handleAccountEvent($event, $contracts);
    }
}
