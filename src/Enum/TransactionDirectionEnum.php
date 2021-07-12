<?php

namespace NftPortfolioTracker\Enum;

class TransactionDirectionEnum
{
    public const IN = 0;
    public const OUT = 1;

    public const DIRECTIONS = [
        self::IN => 'incoming',
        self::OUT => 'outgoing',
    ];
}