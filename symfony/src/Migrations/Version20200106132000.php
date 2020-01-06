<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200106132000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE item ALTER img_links TYPE TEXT');
        $this->addSql('ALTER TABLE item ALTER img_links DROP DEFAULT');
        $this->addSql('ALTER TABLE item ALTER created SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('COMMENT ON COLUMN item.img_links IS NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE item ALTER img_links TYPE TEXT');
        $this->addSql('ALTER TABLE item ALTER img_links DROP DEFAULT');
        $this->addSql('ALTER TABLE item ALTER created DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN item.img_links IS \'(DC2Type:simple_array)\'');
    }
}
