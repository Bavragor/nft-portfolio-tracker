<?php

namespace NftPortfolioTracker\Event\Transaction;

abstract class TransactionAddedEvent
{
    private string $from;
    private string $to;
    private string $transactionHash;
    private int $blockNumber;
    private string $tokenId;
    private string $tokenSymbol;
    private int $priceInWei;
    private int $gasPriceInWei;
    private int $gasUsed;
    private int $timestamp;
    private string $contract;

    protected function __construct(
        string $from,
        string $to,
        string $transactionHash,
        int $blockNumber,
        string $tokenId,
        string $tokenSymbol,
        int $priceInWei,
        int $gasPriceInWei,
        int $gasUsed,
        int $timestamp,
        string $contract
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->transactionHash = $transactionHash;
        $this->blockNumber = $blockNumber;
        $this->tokenId = $tokenId;
        $this->tokenSymbol = $tokenSymbol;
        $this->priceInWei = $priceInWei;
        $this->gasPriceInWei = $gasPriceInWei;
        $this->gasUsed = $gasUsed;
        $this->timestamp = $timestamp;
        $this->contract = $contract;
    }

    /**
     * @return static
     */
    public static function create(
        string $from,
        string $to,
        string $transactionHash,
        int $blockNumber,
        string $tokenId,
        string $tokenSymbol,
        int $priceInWei,
        int $gasPriceInWei,
        int $gasUsed,
        int $timestamp,
        string $contract
    ): self {
        return new static(
            $from,
            $to,
            $transactionHash,
            $blockNumber,
            $tokenId,
            $tokenSymbol,
            $priceInWei,
            $gasPriceInWei,
            $gasUsed,
            $timestamp,
            $contract
        );
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getTransactionHash(): string
    {
        return $this->transactionHash;
    }

    public function getBlockNumber(): int
    {
        return $this->blockNumber;
    }

    public function getTokenId(): string
    {
        return $this->tokenId;
    }

    public function getTokenSymbol(): string
    {
        return $this->tokenSymbol;
    }

    public function getPriceInWei(): int
    {
        return $this->priceInWei;
    }

    public function getGasPriceInWei(): int
    {
        return $this->gasPriceInWei;
    }

    public function getGasUsed(): int
    {
        return $this->gasUsed;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getContract(): string
    {
        return $this->contract;
    }
}
