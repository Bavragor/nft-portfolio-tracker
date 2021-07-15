<?php

namespace NftPortfolioTracker\Handler\Transaction;

use NftPortfolioTracker\Entity\AccountTransaction;
use NftPortfolioTracker\Event\Transaction\TransactionAddedEvent;
use NftPortfolioTracker\Event\Transaction\TransactionInEvent;
use NftPortfolioTracker\Event\Transaction\TransactionOutEvent;
use NftPortfolioTracker\Repository\AccountTransactionRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TransactionAddedEventHandler implements EventSubscriberInterface
{
    private AccountTransactionRepository $transactionRepository;

    public function __construct(AccountTransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TransactionInEvent::class => 'handle',
            TransactionOutEvent::class => 'handle'
        ];
    }

    public function handle(TransactionAddedEvent $event): void
    {
        if (!$event instanceof TransactionInEvent && !$event instanceof TransactionOutEvent) {
            throw new \RuntimeException('Invalid event of class: ' . get_class($event));
        }

        if ($event instanceof TransactionInEvent) {
            $transactionPrice = -1 * abs($event->getPriceInWei());
        }

        if ($event instanceof TransactionOutEvent) {
            $transactionPrice = $event->getPriceInWei();
        }

        $transaction = new AccountTransaction();
        $transaction->setTransactionHash($event->getTransactionHash());
        $transaction->setTokenId($event->getTokenId());
        $transaction->setTokenSymbol($event->getTokenSymbol());
        $transaction->setFromAddress($event->getFrom());
        $transaction->setToAddress($event->getTo());
        $transaction->setTimestamp($event->getTimestamp());
        $transaction->setBlockNumber($event->getBlockNumber());
        $transaction->setPriceInWei($transactionPrice);
        $transaction->setGasPriceInWei($event->getGasPriceInWei());
        $transaction->setGasUsed($event->getGasUsed());

        $this->transactionRepository->save($transaction);
    }
}
