<?php

namespace NftPortfolioTracker\Handler\Account;

use NftPortfolioTracker\Entity\AccountAsset;
use NftPortfolioTracker\Event\Account\AccountAssetsChangedEvent;
use NftPortfolioTracker\Repository\AccountAssetRepository;
use NftPortfolioTracker\Repository\AccountTransactionRepository;
use NftPortfolioTracker\Repository\ProjectRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountAssetsChangedEventProjector implements EventSubscriberInterface
{
    private ProjectRepository $projectRepository;
    private AccountTransactionRepository $transactionRepository;
    private AccountAssetRepository $accountAssetRepository;

    public function __construct(ProjectRepository $projectRepository, AccountTransactionRepository $transactionRepository, AccountAssetRepository $accountAssetRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->transactionRepository = $transactionRepository;
        $this->accountAssetRepository = $accountAssetRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AccountAssetsChangedEvent::class => 'handle'
        ];
    }

    public function handle(AccountAssetsChangedEvent $event): void
    {
        $this->accountAssetRepository->truncateTable($event->getAddress());

        $transactions = $this->transactionRepository->getTransactionsByAccount($event->getAddress());

        foreach ($transactions as $transaction) {
            $this->accountAssetRepository->save(AccountAsset::createFromTransactionWithAccountAddress($event->getAddress(), $transaction));
        }
    }
}