<?php

namespace NftPortfolioTracker\Event\Transaction;

final class TransactionInEvent extends TransactionAddedEvent
{
    public static function createFromArrayWithAccount(string $account, array $transaction): self
    {
        return parent::create(
            $transaction['from'],
            $account,
            $transaction['hash'],
            $transaction['blockNumber'],
            $transaction['tokenID'],
            $transaction['tokenSymbol'],
            $transaction['value'],
            $transaction['gasPrice'],
            $transaction['gasUsed'],
            $transaction['timeStamp'],
            $transaction['contractAddress'],
        );
    }
}
