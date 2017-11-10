<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171110232511 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quote_generic_concept DROP FOREIGN KEY FK_B4A7AD71DB805178');
        $this->addSql('ALTER TABLE quote_generic_concept ADD CONSTRAINT FK_B4A7AD71DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quote_generic_concept DROP FOREIGN KEY FK_B4A7AD71DB805178');
        $this->addSql('ALTER TABLE quote_generic_concept ADD CONSTRAINT FK_B4A7AD71DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id)');
    }
}
