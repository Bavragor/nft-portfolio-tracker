<?php

namespace NftPortfolioTracker\Etherscan;

final class Ethereum
{
    public const WEI = 1000000000000000000;
    public const KWEI = 1000000000000000;
    public const MWEI = 1000000000000;
    public const GWEI = 1000000000;
    public const SZABO = 1000000;
    public const FINNEY = 1000;
    public const KETHER = 0.001;
    public const METHER = 0.000001;

    public static function convertWeiToEther(int $valueInWei): float
    {
        return $valueInWei / self::WEI;
    }
}