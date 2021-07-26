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
    private ProjectRepository $projectRepository;

    public function __construct(AccountTransactionRepository $transactionRepository, AccountAssetRepository $accountAssetRepository, AssetPriceRepository $assetPriceRepository, ProjectRepository $projectRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountAssetRepository = $accountAssetRepository;
        $this->assetPriceRepository = $assetPriceRepository;
        $this->projectRepository = $projectRepository;
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

        $projects = [];

        foreach ($transactions as $transaction) {
            if (!array_key_exists($transaction->getContract(), $projects)) {
                $projects[$transaction->getContract()] = $this->projectRepository->findOneBy(['contract' => $transaction->getContract()]);
            }

            $accountAsset = AccountAsset::createFromTransactionWithAccountAddress($event->getAddress(), $transaction);

            $assetPrice = AssetPrice::createFromAccountAsset($accountAsset);

            if ($projects[$transaction->getContract()] !== null) {
                $assetPrice->setPrice($projects[$transaction->getContract()]->getFloorPrice());
                $assetPrice->setCreatedBy($transaction->getContract());
            }

            $this->accountAssetRepository->save($accountAsset);
            $this->assetPriceRepository->save($assetPrice);
        }
    }
}
