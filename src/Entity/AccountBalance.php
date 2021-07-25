<?php

namespace NftPortfolioTracker\Entity;

use Doctrine\ORM\Mapping as ORM;
use NftPortfolioTracker\Etherscan\Ethereum;
use NftPortfolioTracker\Repository\AccountBalanceRepository;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=AccountBalanceRepository::class)
 */
class AccountBalance
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="float")
     */
    private float $balance;

    /**
     * @ORM\Column(type="integer")
     */
    private int $balanceInWei;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $projectName;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private ?string $tokenSymbol;

    /**
     * @ORM\Column(type="integer")
     */
    private int $usedGasInWei;

    /**
     * @ORM\Column(type="float")
     */
    private float $usedGas;

    /**
     * @ORM\Column(type="string", length=42)
     */
    private string $account;

    public function __construct(string $account, float $balance, int $balanceInWei, float $usedGas, int $usedGasInWei, ?string $projectName, ?string $tokenSymbol)
    {
        $this->id = Uuid::v4();

        $this->balance = $balance;
        $this->balanceInWei = $balanceInWei;
        $this->tokenSymbol = $tokenSymbol;
        $this->projectName = $projectName;
        $this->usedGasInWei = $usedGasInWei;
        $this->usedGas = $usedGas;
        $this->account = $account;
    }


    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function getBalanceInWei(): ?int
    {
        return $this->balanceInWei;
    }

    public function getTokenSymbol(): ?string
    {
        return $this->tokenSymbol;
    }

    public function getUsedGasInWei(): ?int
    {
        return $this->usedGasInWei;
    }

    public function getUsedGas(): ?float
    {
        return $this->usedGas;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function setProjectName(?string $projectName): AccountBalance
    {
        $this->projectName = $projectName;
        return $this;
    }
}
