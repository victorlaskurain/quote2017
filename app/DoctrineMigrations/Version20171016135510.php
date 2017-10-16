<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171016135510 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quote_generic_concept (id INT AUTO_INCREMENT NOT NULL, quote_id INT NOT NULL, `order` INT NOT NULL, description VARCHAR(255) NOT NULL, amount NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, INDEX IDX_B4A7AD71DB805178 (quote_id), UNIQUE INDEX UNIQ_B4A7AD71DB805178F5299398 (quote_id, `order`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quote_generic_concept ADD CONSTRAINT FK_B4A7AD71DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id)');
        $this->addSql('ALTER TABLE quote CHANGE shipping shipping NUMERIC(10, 3) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE quote_generic_concept');
        $this->addSql('ALTER TABLE quote CHANGE shipping shipping NUMERIC(8, 2) NOT NULL');
    }
}
