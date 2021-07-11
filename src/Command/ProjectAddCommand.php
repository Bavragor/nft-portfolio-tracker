<?php

namespace NftPortfolioTracker\Command;

use NftPortfolioTracker\Entity\Project;
use NftPortfolioTracker\Repository\ProjectRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProjectAddCommand extends Command
{
    protected static $defaultName = 'project:add';
    protected static $defaultDescription = 'Add a short description for your command';

    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        parent::__construct();

        $this->projectRepository = $projectRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('contract', InputArgument::REQUIRED, 'Contract')
            ->addArgument('tokenName', InputArgument::REQUIRED, 'Token Name')
            ->addArgument('tokenSymbol', InputArgument::REQUIRED, 'Token Symbol')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $project = new Project();
        $project
            ->setName($input->getArgument('name'))
            ->setContract($input->getArgument('contract'))
            ->setTokenName($input->getArgument('tokenName'))
            ->setTokenSymbol($input->getArgument('tokenSymbol'))
        ;

        $this->projectRepository->save($project);

        return Command::SUCCESS;
    }
}
