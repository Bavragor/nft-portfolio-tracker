<?php

namespace NftPortfolioTracker\Entity;

use Doctrine\ORM\Mapping as ORM;
use NftPortfolioTracker\Repository\AccountRepository;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=42)
     */
    private $address;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public static function createFromUser(AccountUser $user): self
    {
        $account = new self();
        $account
            ->setName($user->getUserIdentifier())
            ->setAddress($user->getAddress())
        ;

        return $account;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
