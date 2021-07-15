<?php

namespace NftPortfolioTracker\Entity;

use Doctrine\ORM\Mapping as ORM;
use NftPortfolioTracker\Repository\NftEventProposalRepository;

/**
 * @ORM\Entity(repositoryClass=NftEventProposalRepository::class)
 */
class NftEventProposal extends NftEvent
{
}
