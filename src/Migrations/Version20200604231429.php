<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200604231429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scope (id INT AUTO_INCREMENT NOT NULL, grants VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scope_user (scope_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_30144616682B5931 (scope_id), INDEX IDX_30144616A76ED395 (user_id), PRIMARY KEY(scope_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scope_user ADD CONSTRAINT FK_30144616682B5931 FOREIGN KEY (scope_id) REFERENCES scope (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scope_user ADD CONSTRAINT FK_30144616A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scope_user DROP FOREIGN KEY FK_30144616682B5931');
        $this->addSql('DROP TABLE scope');
        $this->addSql('DROP TABLE scope_user');
    }
}
