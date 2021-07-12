<?php

namespace NftPortfolioTracker\Handler\Account;

use NftPortfolioTracker\Entity\AccountBalance;
use NftPortfolioTracker\Etherscan\Ethereum;
use NftPortfolioTracker\Event\Account\AccountBalanceChangedEvent;
use NftPortfolioTracker\Repository\AccountBalanceRepository;
use NftPortfolioTracker\Repository\AccountTransactionRepository;
use NftPortfolioTracker\Repository\ProjectRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountBalanceChangedEventProjector implements EventSubscriberInterface
{
    private ProjectRepository $projectRepository;
    private AccountTransactionRepository $transactionRepository;
    private AccountBalanceRepository $accountBalanceRepository;

    public function __construct(ProjectRepository $projectRepository, AccountTransactionRepository $transactionRepository, AccountBalanceRepository $accountBalanceRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->transactionRepository = $transactionRepository;
        $this->accountBalanceRepository = $accountBalanceRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AccountBalanceChangedEvent::class => 'handle'
        ];
    }

    public function handle(AccountBalanceChangedEvent $event): void
    {
        $this->accountBalanceRepository->truncateTable($event->getAddress());

        $projects = $this->projectRepository->getDistinctTokenSymbols();

        $balance = 0;
        $usedGas = 0;

        foreach ($projects as $project) {
            $transactions = $this->transactionRepository->getTransactionsByAccountAndTokenSymbols($event->getAddress(), $project['tokenSymbol']);

            if (count($transactions) === 0) {
                continue;
            }

            $balanceForProject = 0;
            $usedGasForProject = 0;

            foreach ($transactions as $transaction) {
                $usedGasForProject += ($transaction->getGasPriceInWei() * $transaction->getGasUsed()) / Ethereum::WEI;
                $balanceForProject += ($transaction->getPriceInWei() / Ethereum::WEI) - $usedGasForProject;
            }

            $balance += $balanceForProject;
            $usedGas += $usedGasForProject;

            $this->accountBalanceRepository->save(new AccountBalance(
                $event->getAddress(),
                $balanceForProject,
                0,
                $usedGasForProject,
                0,
                $project['tokenSymbol']
            ));
        }

        $this->accountBalanceRepository->save(new AccountBalance(
            $event->getAddress(),
            $balance,
            0,
            $usedGas,
            0,
            null
        ));
    }
}