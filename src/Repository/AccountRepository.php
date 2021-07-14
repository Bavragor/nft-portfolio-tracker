<?php

namespace NftPortfolioTracker\Repository;

use NftPortfolioTracker\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use NftPortfolioTracker\Entity\AccountUser;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function delete(string $address): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $tableName = $this->getEntityManager()->getClassMetadata(Account::class)->getTableName();

        $connection->delete($tableName, ['address' => $address]);
    }

    public function createOrGetAccountByUser(AccountUser $user): Account
    {
        $existingAccount = $this->findOneBy(['address' => strtolower(trim($user->getAddress()))]);

        if ($existingAccount !== null) {
            return $existingAccount;
        }

        $account = Account::createFromUser($user);

        $this->getEntityManager()->persist($account);
        $this->getEntityManager()->flush();

        return $account;
    }
}
