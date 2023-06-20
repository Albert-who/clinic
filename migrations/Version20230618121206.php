<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230618121206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctorTOservice DROP FOREIGN KEY FK_C3CFA66FED5CA9E6');
        $this->addSql('ALTER TABLE doctorTOservice ADD CONSTRAINT FK_C3CFA66FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctorTOservice DROP FOREIGN KEY FK_C3CFA66FED5CA9E6');
        $this->addSql('ALTER TABLE doctorTOservice ADD CONSTRAINT FK_C3CFA66FED5CA9E6 FOREIGN KEY (service_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
