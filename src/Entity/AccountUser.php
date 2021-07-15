<?php

namespace NftPortfolioTracker\Entity;

use Doctrine\ORM\Mapping as ORM;
use NftPortfolioTracker\Repository\AccountUserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=AccountUserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class AccountUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private string $timezone = 'UTC';

    /**
     * @ORM\OneToOne(targetEntity="NftPortfolioTracker\Entity\Account")
     */
    private Account $account;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    private string $address;

    private string $captchaCode;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): AccountUser
    {
        $this->account = $account;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): AccountUser
    {
        $this->address = $address;
        return $this;
    }

    public function getCaptchaCode(): string
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode(string $captchaCode): AccountUser
    {
        $this->captchaCode = $captchaCode;
        return $this;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): AccountUser
    {
        $this->timezone = $timezone;
        return $this;
    }
}
