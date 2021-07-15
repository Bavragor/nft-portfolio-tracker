<?php

namespace NftPortfolioTracker\Event\Transaction;

final class TransactionOutEvent extends TransactionAddedEvent
{
    public static function createFromArrayWithAccount(string $account, array $transaction): self
    {
        return parent::create(
            $account,
            $transaction['to'],
            $transaction['hash'],
            $transaction['blockNumber'],
            $transaction['tokenID'],
            $transaction['tokenSymbol'],
            $transaction['value'],
            $transaction['gasPrice'],
            $transaction['gasUsed'],
            $transaction['timeStamp'],
        );
    }
}
