<?php

namespace NftPortfolioTracker\Repository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use NftPortfolioTracker\Entity\AccountTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
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
}
