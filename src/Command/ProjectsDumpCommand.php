<?php

namespace NftPortfolioTracker\Command;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use NftPortfolioTracker\Entity\Project;
use NftPortfolioTracker\Repository\ProjectRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProjectsDumpCommand extends Command
{
    protected static $defaultName = 'projects:dump';
    protected static $defaultDescription = 'Add a short description for your command';

    private ProjectRepository $projectRepository;

    /**
     * ProjectsDumpCommand constructor.
     * @param ProjectRepository $projectRepository
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        parent::__construct();
        $this->projectRepository = $projectRepository;
    }


    protected function configure(): void
    {
        $this->addArgument('table', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Project $project */
        foreach ($this->projectRepository->findAll() as $originalProject) {
            $dump = '';

            $dump .= '$project = new Project();';
            $dump .= '$project->setName(\'' . $originalProject->getName() . '\');';
            $dump .= '$project->setTokenSymbol(\'' . $originalProject->getTokenSymbol() . '\');';
            $dump .= '$project->setTokenName(\'' . $originalProject->getTokenName() . '\');';
            $dump .= '$project->setContract(\'' . $originalProject->getContract() . '\');';
            $dump .= '$project->setEtherscanUrl(\'' . $originalProject->getEtherscanUrl() . '\');';

            if ($originalProject->getDescription() !== null) {
                $dump .= '$project->setDescription(\'' . $originalProject->getDescription() . '\');';
            } else {
                $dump .= '$project->setDescription(null);';
            }

            $dump .= '$em->persist($project);';

            $output->writeln($dump);
        }

        $dump .= '$em->flush();';

        $output->writeln($dump);

        return Command::SUCCESS;
    }
}
