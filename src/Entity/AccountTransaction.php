<?php

namespace NftPortfolioTracker\Entity;

use Doctrine\ORM\Mapping as ORM;
use NftPortfolioTracker\Repository\AccountTransactionRepository;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=AccountTransactionRepository::class)
 */
class AccountTransaction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $timestamp;

    /**
     * @ORM\Column(type="string", length=42)
     */
    private string $fromAddress;

    /**
     * @ORM\Column(type="string", length=42)
     */
    private string $toAddress;

    /**
     * @ORM\Column(type="string", length=42)
     */
    private string $contract;

    /**
     * @ORM\Column(type="string", length=66)
     */
    private string $transactionHash;

    /**
     * @ORM\Column(type="integer")
     */
    private int $blockNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $tokenId;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $tokenSymbol;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private int $priceInWei;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private int $gasPriceInWei;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private int $gasUsed;

    /**
     * @ORM\Column(type="integer")
     */
    private int $direction;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getFromAddress(): string
    {
        return $this->fromAddress;
    }

    public function setFromAddress(string $fromAddress): self
    {
        $this->fromAddress = $fromAddress;

        return $this;
    }

    public function getToAddress(): string
    {
        return $this->toAddress;
    }

    public function setToAddress(string $toAddress): self
    {
        $this->toAddress = $toAddress;

        return $this;
    }

    public function getTransactionHash(): string
    {
        return $this->transactionHash;
    }

    public function setTransactionHash(string $transactionHash): self
    {
        $this->transactionHash = $transactionHash;

        return $this;
    }

    public function getBlockNumber(): int
    {
        return $this->blockNumber;
    }

    public function setBlockNumber(int $blockNumber): self
    {
        $this->blockNumber = $blockNumber;

        return $this;
    }

    public function getTokenId(): string
    {
        return $this->tokenId;
    }

    public function setTokenId(string $tokenId): self
    {
        $this->tokenId = $tokenId;

        return $this;
    }

    public function getPriceInWei(): int
    {
        return $this->priceInWei;
    }

    public function setPriceInWei(int $priceInWei): self
    {
        $this->priceInWei = $priceInWei;

        return $this;
    }

    public function getGasPriceInWei(): int
    {
        return $this->gasPriceInWei;
    }

    public function setGasPriceInWei(int $gasPriceInWei): self
    {
        $this->gasPriceInWei = $gasPriceInWei;

        return $this;
    }

    public function getGasUsed(): int
    {
        return $this->gasUsed;
    }

    public function setGasUsed(int $gasUsed): self
    {
        $this->gasUsed = $gasUsed;

        return $this;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): AccountTransaction
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getTokenSymbol(): string
    {
        return $this->tokenSymbol;
    }

    public function setTokenSymbol(string $tokenSymbol): AccountTransaction
    {
        $this->tokenSymbol = $tokenSymbol;
        return $this;
    }

    public function getDirection(): int
    {
        return $this->direction;
    }

    public function setDirection(int $direction): AccountTransaction
    {
        $this->direction = $direction;
        return $this;
    }

    public function getContract(): string
    {
        return $this->contract;
    }

    public function setContract(string $contract): AccountTransaction
    {
        $this->contract = $contract;
        return $this;
    }
}
