<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230604183033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointments (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, service_id INT DEFAULT NULL, date DATE NOT NULL, Duser_id INT DEFAULT NULL, INDEX IDX_6A41727AF2F71F95 (Duser_id), INDEX IDX_6A41727AA76ED395 (user_id), INDEX IDX_6A41727AED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctorTOservice (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, Duser_id INT DEFAULT NULL, INDEX IDX_C3CFA66FF2F71F95 (Duser_id), INDEX IDX_C3CFA66FED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, Duser_id INT DEFAULT NULL, INDEX IDX_CAC822D9F2F71F95 (Duser_id), INDEX IDX_CAC822D9ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_doctor TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_6A41727AF2F71F95 FOREIGN KEY (Duser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_6A41727AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_6A41727AED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE doctorTOservice ADD CONSTRAINT FK_C3CFA66FF2F71F95 FOREIGN KEY (Duser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE doctorTOservice ADD CONSTRAINT FK_C3CFA66FED5CA9E6 FOREIGN KEY (service_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D9F2F71F95 FOREIGN KEY (Duser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D9ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727AF2F71F95');
        $this->addSql('ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727AA76ED395');
        $this->addSql('ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727AED5CA9E6');
        $this->addSql('ALTER TABLE doctorTOservice DROP FOREIGN KEY FK_C3CFA66FF2F71F95');
        $this->addSql('ALTER TABLE doctorTOservice DROP FOREIGN KEY FK_C3CFA66FED5CA9E6');
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D9F2F71F95');
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D9ED5CA9E6');
        $this->addSql('DROP TABLE appointments');
        $this->addSql('DROP TABLE doctorTOservice');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
