<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190427151215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assignment (id INT AUTO_INCREMENT NOT NULL, interim_id INT DEFAULT NULL, contract_id INT NOT NULL, rating INT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_30C544BA29C96BD8 (interim_id), UNIQUE INDEX UNIQ_30C544BA2576E0FD (contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, interim_id INT NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_E98F285929C96BD8 (interim_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interim (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, firstname VARCHAR(80) NOT NULL, mail VARCHAR(255) NOT NULL, zipcode VARCHAR(5) NOT NULL, city VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA29C96BD8 FOREIGN KEY (interim_id) REFERENCES interim (id)');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA2576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F285929C96BD8 FOREIGN KEY (interim_id) REFERENCES interim (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BA2576E0FD');
        $this->addSql('ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BA29C96BD8');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F285929C96BD8');
        $this->addSql('DROP TABLE assignment');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE interim');
    }
}
