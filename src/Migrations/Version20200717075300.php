<?php

declare(strict_types=1);

namespace App\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200717075300 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE checklist DROP FOREIGN KEY FK_5C696D2FA76ED395');
        $this->addSql('ALTER TABLE checklist ADD CONSTRAINT FK_5C696D2FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user RENAME INDEX idx_8d93d64919550dce TO IDX_8D93D649D918D16E');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE checklist DROP FOREIGN KEY FK_5C696D2FA76ED395');
        $this->addSql('ALTER TABLE checklist ADD CONSTRAINT FK_5C696D2FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user RENAME INDEX idx_8d93d649d918d16e TO IDX_8D93D64919550DCE');
    }
}
