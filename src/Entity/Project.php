<?php

namespace NftPortfolioTracker\Entity;

use NftPortfolioTracker\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $tokenName;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $tokenSymbol;

    /**
     * @ORM\Column(type="integer")
     */
    private int $tokenDecimal = 0;

    /**
     * @ORM\Column(type="string", length=42)
     */
    private string $contract;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTokenName(): ?string
    {
        return $this->tokenName;
    }

    public function setTokenName(string $tokenName): self
    {
        $this->tokenName = $tokenName;

        return $this;
    }

    public function getTokenSymbol(): ?string
    {
        return $this->tokenSymbol;
    }

    public function setTokenSymbol(string $tokenSymbol): self
    {
        $this->tokenSymbol = $tokenSymbol;

        return $this;
    }

    public function getTokenDecimal(): ?int
    {
        return $this->tokenDecimal;
    }

    public function setTokenDecimal(int $tokenDecimal): self
    {
        $this->tokenDecimal = $tokenDecimal;

        return $this;
    }

    public function getContract(): ?string
    {
        return $this->contract;
    }

    public function setContract(string $contract): self
    {
        $this->contract = $contract;

        return $this;
    }
}
