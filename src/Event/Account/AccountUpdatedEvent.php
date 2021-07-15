<?php

namespace NftPortfolioTracker\Event\Account;

final class AccountUpdatedEvent
{
    private string $address;

    /**
     * @var string[]
     */
    private array $contracts;

    private function __construct(string $address, array $contracts)
    {
        $this->address = $address;
        $this->contracts = $contracts;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string[]
     */
    public function getContracts(): array
    {
        return $this->contracts;
    }

    public static function create(string $address, array $contracts): self
    {
        return new self($address, $contracts);
    }
}
