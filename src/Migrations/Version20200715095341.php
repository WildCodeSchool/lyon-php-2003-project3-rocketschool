<?php

declare(strict_types=1);

namespace App\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200715095341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD accounts_duration_id INT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64919550DCE FOREIGN KEY (accounts_duration_id) REFERENCES accounts_duration (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64919550DCE ON user (accounts_duration_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64919550DCE');
        $this->addSql('DROP INDEX IDX_8D93D64919550DCE ON user');
        $this->addSql('ALTER TABLE user DROP accounts_duration_id');
    }
}
