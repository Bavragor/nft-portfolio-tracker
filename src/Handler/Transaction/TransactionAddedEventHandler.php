<?php

namespace NftPortfolioTracker\Handler\Transaction;

use NftPortfolioTracker\Entity\AccountTransaction;
use NftPortfolioTracker\Enum\TransactionDirectionEnum;
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

        $transaction = new AccountTransaction();
        $transaction->setTransactionHash($event->getTransactionHash());
        $transaction->setTokenId($event->getTokenId());
        $transaction->setTokenSymbol($event->getTokenSymbol());
        $transaction->setFromAddress($event->getFrom());
        $transaction->setToAddress($event->getTo());
        $transaction->setTimestamp($event->getTimestamp());
        $transaction->setBlockNumber($event->getBlockNumber());
        $transaction->setPriceInWei($event->getPriceInWei());
        $transaction->setGasPriceInWei($event->getGasPriceInWei());
        $transaction->setGasUsed($event->getGasUsed());
        $transaction->setContract($event->getContract());

        if ($event instanceof TransactionInEvent) {
            $transaction->setDirection(TransactionDirectionEnum::IN);
        }

        if ($event instanceof TransactionOutEvent) {
            $transaction->setDirection(TransactionDirectionEnum::OUT);
        }

        $this->transactionRepository->save($transaction);
    }
}
