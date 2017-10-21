<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171021144351 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quote ADD number_of_day INT NOT NULL, ADD accepted TINYINT(1) DEFAULT \'0\' NOT NULL, ADD drill NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD lathe NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD forge NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD saw NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD annealing NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD cementation NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD weight NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD price NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD milling NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD threading NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD commissions NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD grinding NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, ADD hardening NUMERIC(10, 3) DEFAULT \'0\' NOT NULL, CHANGE shipping shipping NUMERIC(10, 3) DEFAULT \'0\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quote DROP number_of_day, DROP accepted, DROP drill, DROP lathe, DROP forge, DROP saw, DROP annealing, DROP cementation, DROP weight, DROP price, DROP milling, DROP threading, DROP commissions, DROP grinding, DROP hardening, CHANGE shipping shipping NUMERIC(10, 3) NOT NULL');
    }
}
