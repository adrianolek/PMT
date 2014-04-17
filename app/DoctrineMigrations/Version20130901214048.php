<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130901214048 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE pmt_tasks ADD position INT NOT NULL");
        $this->addSql("ALTER TABLE pmt_tasks_users DROP PRIMARY KEY");
        $this->addSql("ALTER TABLE pmt_tasks_users ADD PRIMARY KEY (task_id, user_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE pmt_tasks DROP position");
        $this->addSql("ALTER TABLE pmt_tasks_users DROP PRIMARY KEY");
        $this->addSql("ALTER TABLE pmt_tasks_users ADD PRIMARY KEY (user_id, task_id)");
    }
}
