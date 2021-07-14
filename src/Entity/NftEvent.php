<?php

namespace NftPortfolioTracker\Entity;

use DateTime;
use DateTimeInterface;
use NftPortfolioTracker\Repository\NftEventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=NftEventRepository::class)
 */
class NftEvent
{
    public const DATETIME_FORMAT = 'Y-m-d\TH:i:s+00:00';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $eventDateStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $eventDateEnd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $twitterUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $platform;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $initialPrice;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $currency;

    public function __construct()
    {
        $this->created = new DateTime();
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getEventDateStart(): ?DateTimeInterface
    {
        return $this->eventDateStart;
    }

    public function setEventDateStart(?DateTimeInterface $eventDateStart): NftEvent
    {
        $this->eventDateStart = $eventDateStart;
        return $this;
    }

    public function getEventDateEnd(): ?DateTimeInterface
    {
        return $this->eventDateEnd;
    }

    public function setEventDateEnd(?DateTimeInterface $eventDateEnd): NftEvent
    {
        $this->eventDateEnd = $eventDateEnd;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): NftEvent
    {
        $this->name = $name;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): NftEvent
    {
        $this->url = $url;
        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(?string $platform): NftEvent
    {
        $this->platform = $platform;
        return $this;
    }

    public function getInitialPrice(): ?float
    {
        return $this->initialPrice;
    }

    public function setInitialPrice(?float $initialPrice): NftEvent
    {
        $this->initialPrice = $initialPrice;
        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): NftEvent
    {
        $this->currency = $currency;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): NftEvent
    {
        $this->type = $type;
        return $this;
    }

    public function getTwitterUrl(): ?string
    {
        return $this->twitterUrl;
    }

    public function setTwitterUrl(?string $twitterUrl): NftEvent
    {
        $this->twitterUrl = $twitterUrl;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId()->jsonSerialize(),
            'eventDateStart' => $this->getEventDateStart() ? $this->getEventDateStart()->format(self::DATETIME_FORMAT) : null,
            'eventDateEnd' => $this->getEventDateEnd() ? $this->getEventDateEnd()->format(self::DATETIME_FORMAT) : null,
            'name' => $this->getName(),
            'type' => $this->getType(),
            'url' => $this->getUrl(),
            'twitterUrl' => $this->getTwitterUrl(),
            'platform' => $this->getPlatform(),
            'initialPrice' => $this->getInitialPrice(),
            'currency' => $this->getCurrency(),
        ];
    }
}
