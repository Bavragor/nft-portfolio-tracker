<?php

namespace NftPortfolioTracker\Entity;

use NftPortfolioTracker\Repository\AssetPriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=AssetPriceRepository::class)
 */
class AssetPrice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $createdBy;

    /**
     * @ORM\Column(type="string", length=42)
     */
    private string $createdByAddress;

    /**
     * @ORM\Column(type="float")
     */
    private float $initialPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $price;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $tokenSymbol;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $tokenId;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public static function createFromAccountAsset(AccountAsset $accountAsset): self
    {
        $assetPrice = new self();
        $assetPrice
            ->setCreatedByAddress($accountAsset->getAccount())
            ->setInitialPrice($accountAsset->getPrice())
            ->setTokenSymbol($accountAsset->getTokenSymbol())
            ->setTokenId($accountAsset->getTokenId())
        ;

        return $assetPrice;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getInitialPrice(): ?float
    {
        return $this->initialPrice;
    }

    public function setInitialPrice(float $initialPrice): self
    {
        $this->initialPrice = $initialPrice;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedByAddress(): ?string
    {
        return $this->createdByAddress;
    }

    public function setCreatedByAddress(string $createdByAddress): self
    {
        $this->createdByAddress = $createdByAddress;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTokenSymbol(): string
    {
        return $this->tokenSymbol;
    }

    public function setTokenSymbol(string $tokenSymbol): AssetPrice
    {
        $this->tokenSymbol = $tokenSymbol;
        return $this;
    }

    public function getTokenId(): string
    {
        return $this->tokenId;
    }

    public function setTokenId(string $tokenId): AssetPrice
    {
        $this->tokenId = $tokenId;
        return $this;
    }
}
