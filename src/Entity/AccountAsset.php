<?php

namespace NftPortfolioTracker\Entity;

use NftPortfolioTracker\Enum\TransactionDirectionEnum;
use NftPortfolioTracker\Etherscan\Ethereum;
use NftPortfolioTracker\Repository\AccountAssetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=AccountAssetRepository::class)
 */
class AccountAsset
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
    private string $account;

    /**
     * @ORM\Column(type="string", length=42)
     */
    private string $fromAddress;

    /**
     * @ORM\Column(type="string", length=42)
     */
    private string $toAddress;

    /**
     * @ORM\Column(type="string", length=66)
     */
    private string $transactionHash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $tokenId;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $tokenSymbol;

    /**
     * @ORM\Column(type="float")
     */
    private float $price;

    /**
     * @ORM\Column(type="float")
     */
    private float $gasPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private int $direction;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public static function createFromTransactionWithAccountAddress(string $account, AccountTransaction $transaction): self
    {
        $accountAsset = new self();
        $accountAsset
            ->setAccount($account)
            ->setTokenSymbol($transaction->getTokenSymbol())
            ->setTransactionHash($transaction->getTransactionHash())
            ->setTimestamp($transaction->getTimestamp())
            ->setTokenId($transaction->getTokenId())
            ->setFromAddress($transaction->getFromAddress())
            ->setToAddress($transaction->getToAddress())
            ->setPrice($transaction->getPriceInWei() / Ethereum::WEI)
            ->setGasPrice(($transaction->getGasPriceInWei() * $transaction->getGasUsed()) / Ethereum::WEI)
        ;

        if ($account === $accountAsset->getToAddress()) {
            $accountAsset->setDirection(TransactionDirectionEnum::IN);
        }

        if ($account === $accountAsset->getFromAddress()) {
            $accountAsset->setDirection(TransactionDirectionEnum::OUT);
        }

        return $accountAsset;
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

    public function getTokenId(): string
    {
        return $this->tokenId;
    }

    public function setTokenId(string $tokenId): self
    {
        $this->tokenId = $tokenId;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getGasPrice(): float
    {
        return $this->gasPrice;
    }

    public function setGasPrice(float $gasPrice): self
    {
        $this->gasPrice = $gasPrice;

        return $this;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getTokenSymbol(): string
    {
        return $this->tokenSymbol;
    }

    public function setTokenSymbol(string $tokenSymbol): self
    {
        $this->tokenSymbol = $tokenSymbol;
        return $this;
    }

    public function getAccount(): string
    {
        return $this->account;
    }

    public function setAccount(string $account): self
    {
        $this->account = $account;
        return $this;
    }

    public function getDirection(): int
    {
        return $this->direction;
    }

    public function setDirection(int $direction): self
    {
        $this->direction = $direction;
        return $this;
    }
}
