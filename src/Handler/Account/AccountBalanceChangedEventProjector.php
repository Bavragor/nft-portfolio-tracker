<?php

namespace NftPortfolioTracker\Handler\Account;

use NftPortfolioTracker\Entity\AccountBalance;
use NftPortfolioTracker\Enum\TransactionDirectionEnum;
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
                $gasPrice = ($transaction['gasPriceInWei'] * $transaction['gasUsed']) / Ethereum::WEI;
                $transactionPrice = ($transaction['priceInWei'] / Ethereum::WEI) - $gasPrice;

                if ((int) $transaction['direction'] === TransactionDirectionEnum::IN) {
                    $transactionPrice = -1 * abs($transactionPrice);
                }

                $usedGasForProject += $gasPrice;
                $balanceForProject += $transactionPrice;
            }

            $balance += $balanceForProject;
            $usedGas += $usedGasForProject;

            $this->accountBalanceRepository->save(new AccountBalance(
                $event->getAddress(),
                $balanceForProject,
                0,
                $usedGasForProject,
                0,
                $project['name'],
                $project['tokenSymbol'],
            ));
        }

        $this->accountBalanceRepository->save(new AccountBalance(
            $event->getAddress(),
            $balance,
            0,
            $usedGas,
            0,
            null,
            null,
        ));
    }
}
