<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131207152010 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE pmt_tasks_texts (
    task_id INT NOT NULL,
    name VARCHAR(255) NULL,
    description LONGTEXT NULL,
    PRIMARY KEY (task_id),
    FULLTEXT(name),
    FULLTEXT(description),
    FULLTEXT(name,description)
) ENGINE=MyISAM;");
      
      $this->addSql("INSERT INTO pmt_tasks_texts SELECT id, name, description FROM pmt_tasks;");
      
      $this->addSql("CREATE TRIGGER insert_tasks AFTER INSERT ON pmt_tasks FOR EACH ROW
INSERT INTO pmt_tasks_texts VALUES (NEW.id, NEW.name, NEW.description);

CREATE TRIGGER update_tasks AFTER UPDATE ON pmt_tasks FOR EACH ROW
UPDATE pmt_tasks_texts SET name = NEW.name, description = NEW.description WHERE task_id = OLD.id;

CREATE TRIGGER delete_tasks AFTER DELETE ON pmt_tasks FOR EACH ROW
DELETE FROM pmt_tasks_texts WHERE task_id = OLD.id;");

    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TRIGGER insert_tasks");
        $this->addSql("DROP TRIGGER update_tasks");
        $this->addSql("DROP TRIGGER delete_tasks");
      
        $this->addSql("DROP TABLE pmt_tasks_texts");
    }
}
