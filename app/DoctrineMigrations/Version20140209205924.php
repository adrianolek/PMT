<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140209205924 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE pmt_projects_users (project_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4ED7FF38166D1F9C (project_id), INDEX IDX_4ED7FF38A76ED395 (user_id), PRIMARY KEY(project_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE pmt_projects_users ADD CONSTRAINT FK_4ED7FF38166D1F9C FOREIGN KEY (project_id) REFERENCES pmt_projects (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE pmt_projects_users ADD CONSTRAINT FK_4ED7FF38A76ED395 FOREIGN KEY (user_id) REFERENCES pmt_users (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE pmt_projects_users");
    }
}
