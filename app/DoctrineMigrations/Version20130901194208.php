<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130901194208 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE pmt_files ADD user_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE pmt_files ADD CONSTRAINT FK_78AC9B4FA76ED395 FOREIGN KEY (user_id) REFERENCES pmt_users (id)");
        $this->addSql("CREATE INDEX IDX_78AC9B4FA76ED395 ON pmt_files (user_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE pmt_files DROP FOREIGN KEY FK_78AC9B4FA76ED395");
        $this->addSql("DROP INDEX IDX_78AC9B4FA76ED395 ON pmt_files");
        $this->addSql("ALTER TABLE pmt_files DROP user_id");
    }
}
