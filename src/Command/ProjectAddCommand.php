<?php

namespace NftPortfolioTracker\Command;

use NftPortfolioTracker\Entity\Project;
use NftPortfolioTracker\Event\Contract\ContractAddedEvent;
use NftPortfolioTracker\Repository\ProjectRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProjectAddCommand extends Command
{
    protected static $defaultName = 'project:add';
    protected static $defaultDescription = 'Add a short description for your command';

    private EventDispatcherInterface $eventDispatcher;
    private ProjectRepository $projectRepository;

    public function __construct(EventDispatcherInterface $eventDispatcher, ProjectRepository $projectRepository)
    {
        parent::__construct();

        $this->eventDispatcher = $eventDispatcher;
        $this->projectRepository = $projectRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('contract', InputArgument::REQUIRED, 'Contract')
            ->addArgument('tokenName', InputArgument::REQUIRED, 'Token Name')
            ->addArgument('tokenSymbol', InputArgument::REQUIRED, 'Token Symbol')
            ->addOption('matic', InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $apiUrl = 'https://api.etherscan.io/api';

        if ($input->getOption('matic')) {
            $apiUrl = 'https://api.polygonscan.com/api';
        }

        $project = new Project();
        $project
            ->setName($input->getArgument('name'))
            ->setContract($input->getArgument('contract'))
            ->setTokenName($input->getArgument('tokenName'))
            ->setTokenSymbol($input->getArgument('tokenSymbol'))
            ->setEtherscanUrl($apiUrl)
        ;

        $this->projectRepository->save($project);

        $this->eventDispatcher->dispatch(ContractAddedEvent::create($project->getContract()));

        return Command::SUCCESS;
    }
}
