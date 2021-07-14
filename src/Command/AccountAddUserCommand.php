<?php

namespace NftPortfolioTracker\Command;

use NftPortfolioTracker\Entity\Account;
use NftPortfolioTracker\Entity\AccountUser;
use NftPortfolioTracker\Repository\AccountRepository;
use NftPortfolioTracker\Repository\AccountUserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class AccountAddUserCommand extends Command
{
    protected static $defaultName = 'account:add:user';
    protected static $defaultDescription = 'Add a short description for your command';

    private AccountRepository $accountRepository;
    private AccountUserRepository $accountUserRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher, AccountRepository $accountRepository, AccountUserRepository $accountUserRepository)
    {
        parent::__construct();

        $this->passwordHasher = $passwordHasher;
        $this->accountRepository = $accountRepository;
        $this->accountUserRepository = $accountUserRepository;
    }

    protected function configure(): void
    {
        // nothing
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $io->ask('Which username?');

        $address = $io->ask('Which wallet?');

        $question = new Question('Which password?');
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        $password = $io->askQuestion($question);

        $admin = $io->ask('Should be admin?', 'no');

        $roles = [];

        if ($admin === 'yes') {
            $roles = ['ROLE_ADMIN'];
        }

        $accountUser = new AccountUser();
        $accountUser
            ->setUsername($username)
            ->setRoles($roles)
            ->setPassword($this->passwordHasher->hashPassword($accountUser, $password))
            ->setAddress($address)
        ;

        $account = $this->accountRepository->createOrGetAccountByUser($accountUser);

        $accountUser->setAccount($account);

        $this->accountUserRepository->save($accountUser);

        return Command::SUCCESS;
    }
}
