<?php

namespace NftPortfolioTracker\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use NftPortfolioTracker\Entity\NftEvent;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method NftEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method NftEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method NftEvent[]    findAll()
 * @method NftEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NftEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NftEvent::class);
    }

    // /**
    //  * @return NftEvent[] Returns an array of NftEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NftEvent
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return NftEvent[]
     */
    public function findBetweenDates(string $startDate, string $endDate): array
    {
        $startDate = new \DateTime($startDate, new \DateTimeZone('UTC'));
        $endDate = new \DateTime($endDate, new \DateTimeZone('UTC'));

        $queryBuilder = $this->createQueryBuilder('nftEvent');
        $queryBuilder
            ->select('nftEvent')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->gte('nftEvent.eventDateStart', $queryBuilder->expr()->literal($startDate->format(NftEvent::DATETIME_FORMAT))),
                    $queryBuilder->expr()->lte('nftEvent.eventDateEnd', $queryBuilder->expr()->literal($endDate->format(NftEvent::DATETIME_FORMAT))),
                )
            )
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
