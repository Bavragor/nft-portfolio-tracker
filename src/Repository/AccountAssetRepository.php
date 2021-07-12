<?php

namespace NftPortfolioTracker\Repository;

use NftPortfolioTracker\Entity\AccountAsset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method AccountAsset|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountAsset|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountAsset[]    findAll()
 * @method AccountAsset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountAssetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountAsset::class);
    }

    public function truncateTable(string $address): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $tableName = $this->getEntityManager()->getClassMetadata(AccountAsset::class)->getTableName();

        $connection->delete($tableName, ['account' => $address]);
    }

    public function save(AccountAsset $transaction): void
    {
        $this->getEntityManager()->persist($transaction);
        $this->getEntityManager()->flush();
    }

    /**
     * @return AccountAsset[]
     */
    public function getAssetsByAccountAndTokenSymbols(string $account, string $tokenSymbol): array
    {
        $queryBuilder = $this->createQueryBuilder('transaction');
        $queryBuilder = $queryBuilder
            ->select('transaction')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('transaction.tokenSymbol', $queryBuilder->expr()->literal($tokenSymbol)),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('transaction.toAddress', $queryBuilder->expr()->literal($account)),
                        $queryBuilder->expr()->eq('transaction.fromAddress', $queryBuilder->expr()->literal($account))
                    )
                )
            )
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
