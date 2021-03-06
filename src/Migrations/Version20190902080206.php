<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190902080206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trassir_nvr ADD facility_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trassir_nvr ADD CONSTRAINT FK_D927D699A7014910 FOREIGN KEY (facility_id) REFERENCES facility (id)');
        $this->addSql('CREATE INDEX IDX_D927D699A7014910 ON trassir_nvr (facility_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trassir_nvr DROP FOREIGN KEY FK_D927D699A7014910');
        $this->addSql('DROP INDEX IDX_D927D699A7014910 ON trassir_nvr');
        $this->addSql('ALTER TABLE trassir_nvr DROP facility_id');
    }
}
