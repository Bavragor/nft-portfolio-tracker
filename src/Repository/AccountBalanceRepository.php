<?php

namespace NftPortfolioTracker\Repository;

use NftPortfolioTracker\Entity\AccountBalance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountBalance|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountBalance|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountBalance[]    findAll()
 * @method AccountBalance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountBalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountBalance::class);
    }

    public function truncateTable(): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL($this->getEntityManager()->getClassMetadata(AccountBalance::class)->getTableName(), false));
    }

    public function save(AccountBalance $accountBalance): void
    {
        $this->getEntityManager()->persist($accountBalance);
        $this->getEntityManager()->flush();
    }
}
