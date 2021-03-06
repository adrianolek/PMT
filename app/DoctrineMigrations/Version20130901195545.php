<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130901195545 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE pmt_tasks_users (user_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_1882DAF4A76ED395 (user_id), INDEX IDX_1882DAF48DB60186 (task_id), PRIMARY KEY(user_id, task_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE pmt_tasks_users ADD CONSTRAINT FK_1882DAF4A76ED395 FOREIGN KEY (user_id) REFERENCES pmt_users (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE pmt_tasks_users ADD CONSTRAINT FK_1882DAF48DB60186 FOREIGN KEY (task_id) REFERENCES pmt_tasks (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE pmt_tasks_users");
    }
}
