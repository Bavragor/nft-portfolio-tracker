<?php

namespace NftPortfolioTracker\Command;

use NftPortfolioTracker\Event\Account\AccountAddedEvent;
use NftPortfolioTracker\Event\Account\AccountUpdatedEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AccountUpdateCommand extends Command
{
    protected static $defaultName = 'account:update';
    protected static $defaultDescription = 'Add a short description for your command';

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct();

        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('address', InputArgument::REQUIRED, 'Ethereum address')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->eventDispatcher->dispatch(AccountUpdatedEvent::create($input->getArgument('address'), []));

        return Command::SUCCESS;
    }
}
