<?php

namespace NftPortfolioTracker\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use NftPortfolioTracker\Entity\AccountTransaction;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method AccountTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountTransaction[]    findAll()
 * @method AccountTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountTransaction::class);
    }

    public function truncateTable(string $address): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $tableName = $this->getEntityManager()->getClassMetadata(AccountTransaction::class)->getTableName();

        $connection->delete($tableName, ['from_address' => $address]);
        $connection->delete($tableName, ['to_address' => $address]);
    }

    public function save(AccountTransaction $transaction): void
    {
        $this->getEntityManager()->persist($transaction);
        $this->getEntityManager()->flush();
    }

    public function getLatestBlockNumberForAccount(string $account): ?int
    {
        $queryBuilder = $this->createQueryBuilder('transaction');
        $queryBuilder = $queryBuilder
            ->select($queryBuilder->expr()->max('transaction.blockNumber'))
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('transaction.toAddress', $queryBuilder->expr()->literal($account)),
                    $queryBuilder->expr()->eq('transaction.fromAddress', $queryBuilder->expr()->literal($account))
                )
            );

        try {
            return $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $noResultException) {
            return null;
        }
    }

    /**
     * @return AccountTransaction[]
     */
    public function getTransactionsByAccountAndTokenSymbols(string $account, string $tokenSymbol): array
    {
        $queryBuilder = $this->createQueryBuilder('transaction');
        $queryBuilder = $queryBuilder
            ->select('any_value(transaction.priceInWei) as priceInWei')
            ->addSelect('any_value(transaction.gasPriceInWei) as gasPriceInWei')
            ->addSelect('any_value(transaction.gasUsed) as gasUsed')
            ->addSelect('any_value(transaction.direction) as direction')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('transaction.tokenSymbol', $queryBuilder->expr()->literal($tokenSymbol)),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('transaction.toAddress', $queryBuilder->expr()->literal($account)),
                        $queryBuilder->expr()->eq('transaction.fromAddress', $queryBuilder->expr()->literal($account))
                    )
                )
            )
            ->addGroupBy('transaction.transactionHash')
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return AccountTransaction[]
     */
    public function getTransactionsByAccount(string $account): array
    {
        $queryBuilder = $this->createQueryBuilder('transaction');
        $queryBuilder = $queryBuilder
            ->select('transaction')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('transaction.toAddress', $queryBuilder->expr()->literal($account)),
                    $queryBuilder->expr()->eq('transaction.fromAddress', $queryBuilder->expr()->literal($account))
                )
            )
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
