<?php

namespace NftPortfolioTracker\Repository;

use NftPortfolioTracker\Entity\AssetPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssetPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetPrice[]    findAll()
 * @method AssetPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssetPrice::class);
    }

    public function truncateTable(string $address): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $tableName = $this->getEntityManager()->getClassMetadata(AssetPrice::class)->getTableName();

        $connection->delete($tableName, ['created_by_address' => $address]);
    }

    public function save(AssetPrice $assetPrice): void
    {
        $this->getEntityManager()->persist($assetPrice);
        $this->getEntityManager()->flush();
    }
}
