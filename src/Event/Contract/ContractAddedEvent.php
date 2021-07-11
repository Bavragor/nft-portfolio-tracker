<?php

namespace NftPortfolioTracker\Event\Contract;

final class ContractAddedEvent
{
    private string $address;

    private function __construct(string $address)
    {
        $this->address = $address;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public static function create(string $address): self
    {
        return new self($address);
    }
}