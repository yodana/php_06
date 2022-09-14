<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220912023852 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D8D93D649');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D8D93D649 FOREIGN KEY (user) REFERENCES user (id);');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D8D93D649');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D8D93D649 FOREIGN KEY (user) REFERENCES post (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
