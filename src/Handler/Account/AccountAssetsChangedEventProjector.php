<?php

namespace NftPortfolioTracker\Handler\Account;

use NftPortfolioTracker\Entity\AccountAsset;
use NftPortfolioTracker\Entity\AssetPrice;
use NftPortfolioTracker\Event\Account\AccountAssetsChangedEvent;
use NftPortfolioTracker\Repository\AccountAssetRepository;
use NftPortfolioTracker\Repository\AccountTransactionRepository;
use NftPortfolioTracker\Repository\AssetPriceRepository;
use NftPortfolioTracker\Repository\ProjectRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountAssetsChangedEventProjector implements EventSubscriberInterface
{
    private AccountTransactionRepository $transactionRepository;
    private AccountAssetRepository $accountAssetRepository;
    private AssetPriceRepository $assetPriceRepository;

    public function __construct(AccountTransactionRepository $transactionRepository, AccountAssetRepository $accountAssetRepository, AssetPriceRepository $assetPriceRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountAssetRepository = $accountAssetRepository;
        $this->assetPriceRepository = $assetPriceRepository;
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
        $this->assetPriceRepository->truncateTable($event->getAddress());

        $transactions = $this->transactionRepository->getTransactionsByAccount($event->getAddress());

        foreach ($transactions as $transaction) {
            $accountAsset = AccountAsset::createFromTransactionWithAccountAddress($event->getAddress(), $transaction);

            $this->accountAssetRepository->save($accountAsset);
            $this->assetPriceRepository->save(AssetPrice::createFromAccountAsset($accountAsset));
        }
    }
}
