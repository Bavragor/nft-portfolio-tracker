<?php

namespace NftPortfolioTracker\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use NftPortfolioTracker\Entity\NftEventProposal;

/**
 * @method NftEventProposal|null find($id, $lockMode = null, $lockVersion = null)
 * @method NftEventProposal|null findOneBy(array $criteria, array $orderBy = null)
 * @method NftEventProposal[]    findAll()
 * @method NftEventProposal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NftEventProposalRepository extends NftEventRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        ServiceEntityRepository::__construct($registry, NftEventProposal::class);
    }
}