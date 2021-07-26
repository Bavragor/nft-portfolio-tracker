<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210726173434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_transaction ADD contract VARCHAR(42) DEFAULT NULL;');
        $this->addSql('UPDATE account_transaction SET account_transaction.contract = (SELECT project.contract FROM project WHERE project.token_symbol = account_transaction.token_symbol) WHERE TRUE;');
        $this->addSql('ALTER TABLE account_transaction ALTER COLUMN contract VARCHAR(42) NOT NULL;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_transaction DROP contract');
    }
}
