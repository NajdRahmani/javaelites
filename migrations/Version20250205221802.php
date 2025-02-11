<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205221802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE home (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, score_max INT NOT NULL, date_limite DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, type VARCHAR(50) NOT NULL, points INT NOT NULL, texte LONGTEXT NOT NULL, ordre INT NOT NULL, INDEX IDX_6033B00B853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_reponse (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, texte LONGTEXT NOT NULL, est_correcte TINYINT(1) NOT NULL, INDEX IDX_4DEC73671E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_quiz_result (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, score INT NOT NULL, soumis_le DATETIME NOT NULL, feedback LONGTEXT DEFAULT NULL, user INT NOT NULL, INDEX IDX_5735F8DF853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_quiz_selection (id INT AUTO_INCREMENT NOT NULL, response_selectionnee_id INT NOT NULL, question_id INT NOT NULL, est_correcte TINYINT(1) NOT NULL, INDEX IDX_FB40A2682C519D31 (response_selectionnee_id), INDEX IDX_FB40A2681E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz_reponse ADD CONSTRAINT FK_4DEC73671E27F6BF FOREIGN KEY (question_id) REFERENCES quiz_question (id)');
        $this->addSql('ALTER TABLE user_quiz_result ADD CONSTRAINT FK_5735F8DF853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE user_quiz_selection ADD CONSTRAINT FK_FB40A2682C519D31 FOREIGN KEY (response_selectionnee_id) REFERENCES quiz_reponse (id)');
        $this->addSql('ALTER TABLE user_quiz_selection ADD CONSTRAINT FK_FB40A2681E27F6BF FOREIGN KEY (question_id) REFERENCES quiz_question (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE quiz_reponse DROP FOREIGN KEY FK_4DEC73671E27F6BF');
        $this->addSql('ALTER TABLE user_quiz_result DROP FOREIGN KEY FK_5735F8DF853CD175');
        $this->addSql('ALTER TABLE user_quiz_selection DROP FOREIGN KEY FK_FB40A2682C519D31');
        $this->addSql('ALTER TABLE user_quiz_selection DROP FOREIGN KEY FK_FB40A2681E27F6BF');
        $this->addSql('DROP TABLE home');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_question');
        $this->addSql('DROP TABLE quiz_reponse');
        $this->addSql('DROP TABLE user_quiz_result');
        $this->addSql('DROP TABLE user_quiz_selection');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
