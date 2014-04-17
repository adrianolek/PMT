<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130828230509 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE pmt_comments (id INT AUTO_INCREMENT NOT NULL, task_id INT DEFAULT NULL, user_id INT DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_AC2A7EA28DB60186 (task_id), INDEX IDX_AC2A7EA2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE pmt_comments ADD CONSTRAINT FK_AC2A7EA28DB60186 FOREIGN KEY (task_id) REFERENCES pmt_tasks (id)");
        $this->addSql("ALTER TABLE pmt_comments ADD CONSTRAINT FK_AC2A7EA2A76ED395 FOREIGN KEY (user_id) REFERENCES pmt_users (id)");
        $this->addSql("ALTER TABLE pmt_tasks ADD user_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE pmt_tasks ADD CONSTRAINT FK_2EC1BE81A76ED395 FOREIGN KEY (user_id) REFERENCES pmt_users (id)");
        $this->addSql("CREATE INDEX IDX_2EC1BE81A76ED395 ON pmt_tasks (user_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE pmt_comments");
        $this->addSql("ALTER TABLE pmt_tasks DROP FOREIGN KEY FK_2EC1BE81A76ED395");
        $this->addSql("DROP INDEX IDX_2EC1BE81A76ED395 ON pmt_tasks");
        $this->addSql("ALTER TABLE pmt_tasks DROP user_id");
    }
}
