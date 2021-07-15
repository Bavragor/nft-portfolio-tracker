<?php

namespace NftPortfolioTracker\Handler\Contract;

use NftPortfolioTracker\Event\Account\AccountAssetsChangedEvent;
use NftPortfolioTracker\Event\Account\AccountBalanceChangedEvent;
use NftPortfolioTracker\Event\Account\AccountUpdatedEvent;
use NftPortfolioTracker\Event\Contract\ContractAddedEvent;
use NftPortfolioTracker\Event\Transaction\TransactionInEvent;
use NftPortfolioTracker\Event\Transaction\TransactionOutEvent;
use NftPortfolioTracker\Repository\AccountRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContractAddedEventHandler implements EventSubscriberInterface
{
    private AccountRepository $accountRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(AccountRepository $accountRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->accountRepository = $accountRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContractAddedEvent::class => 'handle'
        ];
    }

    public function handle(ContractAddedEvent $event): void
    {
        $accounts = $this->accountRepository->findAll();

        foreach ($accounts as $account) {
            $this->eventDispatcher->dispatch(AccountUpdatedEvent::create($account->getAddress(), [$event->getAddress()]));
        }
    }
}
