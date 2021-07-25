<?php

namespace NftPortfolioTracker\Event\Account;

interface AccountEvent
{
    public function getAddress(): string;
}
