<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171117132359 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quote CHANGE shipping shipping NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE drill drill NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE lathe lathe NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE forge forge NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE saw saw NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE annealing annealing NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE cementation cementation NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE weight weight NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE price price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE milling milling NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE threading threading NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE commissions commissions NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE grinding grinding NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE hardening hardening NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE zinc_plating zinc_plating NUMERIC(10, 2) DEFAULT \'0\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quote CHANGE shipping shipping NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE drill drill NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE lathe lathe NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE forge forge NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE saw saw NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE annealing annealing NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE cementation cementation NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE weight weight NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE price price NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE milling milling NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE threading threading NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE commissions commissions NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE grinding grinding NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE hardening hardening NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL, CHANGE zinc_plating zinc_plating NUMERIC(10, 3) DEFAULT \'0.000\' NOT NULL');
    }
}
