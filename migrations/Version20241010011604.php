<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010011604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', UNIQUE INDEX UNIQ_6956883F77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', from_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', to_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', value DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', INDEX IDX_DFEC3F3978CED90B (from_id), INDEX IDX_DFEC3F3930354A65 (to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F3978CED90B FOREIGN KEY (from_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F3930354A65 FOREIGN KEY (to_id) REFERENCES currency (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F3978CED90B');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F3930354A65');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE rate');
    }
}
