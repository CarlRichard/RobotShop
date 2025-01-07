<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250107124354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discount_code ADD valid_from DATETIME DEFAULT NULL, ADD valid_until DATETIME DEFAULT NULL, ADD is_active TINYINT(1) NOT NULL, CHANGE minimum_order_amount minimum_order_amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E997352277153098 ON discount_code (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_E997352277153098 ON discount_code');
        $this->addSql('ALTER TABLE discount_code DROP valid_from, DROP valid_until, DROP is_active, CHANGE minimum_order_amount minimum_order_amount DOUBLE PRECISION NOT NULL');
    }
}
