<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250215121236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse DROP INDEX UNIQ_5FB6DEC7D233E95C, ADD INDEX IDX_5FB6DEC7D233E95C (resultat_id)');
        $this->addSql('ALTER TABLE reponse CHANGE resultat_id resultat_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse DROP INDEX IDX_5FB6DEC7D233E95C, ADD UNIQUE INDEX UNIQ_5FB6DEC7D233E95C (resultat_id)');
        $this->addSql('ALTER TABLE reponse CHANGE resultat_id resultat_id INT DEFAULT NULL');
    }
}
