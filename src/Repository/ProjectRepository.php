<?php

namespace NftPortfolioTracker\Repository;

use NftPortfolioTracker\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function save(Project $project): void
    {
        $this->getEntityManager()->persist($project);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array[]
     */
    public function getDistinctContracts(): array
    {
        return $this->createQueryBuilder('project')
            ->select('project.contract')
            ->groupBy('project.contract')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * @return array[]
     */
    public function getDistinctTokenSymbols(): array
    {
        return $this->createQueryBuilder('project')
            ->select('project.tokenSymbol')
            ->groupBy('project.tokenSymbol')
            ->getQuery()
            ->getScalarResult();
    }
}
