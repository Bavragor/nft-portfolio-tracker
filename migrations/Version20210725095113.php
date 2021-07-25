<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210725095113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_transaction ADD direction INT NOT NULL, CHANGE price_in_wei price_in_wei BIGINT UNSIGNED NOT NULL, CHANGE gas_price_in_wei gas_price_in_wei BIGINT UNSIGNED NOT NULL, CHANGE gas_used gas_used BIGINT UNSIGNED NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_transaction DROP direction, CHANGE price_in_wei price_in_wei INT NOT NULL, CHANGE gas_price_in_wei gas_price_in_wei INT NOT NULL, CHANGE gas_used gas_used INT NOT NULL');
    }
}
