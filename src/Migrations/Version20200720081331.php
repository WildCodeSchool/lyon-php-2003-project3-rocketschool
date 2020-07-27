<?php

declare(strict_types=1);

namespace App\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200720081331 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE faq ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE question CHANGE quizz_id quizz_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) NOT NULL, CHANGE question_order question_order INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE faq DROP image');
        $this->addSql('ALTER TABLE question CHANGE quizz_id quizz_id INT NOT NULL, CHANGE title title LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE question_order question_order INT DEFAULT NULL');
    }
}
