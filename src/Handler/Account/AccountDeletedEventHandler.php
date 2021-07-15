<?php

namespace NftPortfolioTracker\Handler\Account;

use NftPortfolioTracker\Entity\AccountBalance;
use NftPortfolioTracker\Etherscan\Ethereum;
use NftPortfolioTracker\Event\Account\AccountBalanceChangedEvent;
use NftPortfolioTracker\Event\Account\AccountDeletedEvent;
use NftPortfolioTracker\Repository\AccountAssetRepository;
use NftPortfolioTracker\Repository\AccountBalanceRepository;
use NftPortfolioTracker\Repository\AccountRepository;
use NftPortfolioTracker\Repository\AccountTransactionRepository;
use NftPortfolioTracker\Repository\AssetPriceRepository;
use NftPortfolioTracker\Repository\ProjectRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountDeletedEventHandler implements EventSubscriberInterface
{
    private AssetPriceRepository $assetPriceRepository;
    private AccountTransactionRepository $transactionRepository;
    private AccountBalanceRepository $accountBalanceRepository;
    private AccountRepository $accountRepository;
    private AccountAssetRepository $accountAssetRepository;

    public function __construct(AccountRepository $accountRepository, AccountTransactionRepository $transactionRepository, AccountBalanceRepository $accountBalanceRepository, AssetPriceRepository $assetPriceRepository, AccountAssetRepository $accountAssetRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
        $this->accountBalanceRepository = $accountBalanceRepository;
        $this->assetPriceRepository = $assetPriceRepository;
        $this->accountAssetRepository = $accountAssetRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AccountDeletedEvent::class => 'handle'
        ];
    }

    public function handle(AccountDeletedEvent $event): void
    {
        $this->accountBalanceRepository->truncateTable($event->getAddress());
        $this->accountAssetRepository->truncateTable($event->getAddress());
        $this->assetPriceRepository->truncateTable($event->getAddress());
        $this->transactionRepository->truncateTable($event->getAddress());
        $this->accountRepository->delete($event->getAddress());
    }
}
