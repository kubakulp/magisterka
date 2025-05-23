<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241207131816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tic_tac_toe_game ALTER prompt_type DROP DEFAULT');
        $this->addSql('ALTER TABLE tic_tac_toe_move ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE tic_tac_toe_move ALTER move_answer TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tic_tac_toe_game ALTER prompt_type SET DEFAULT \'\'');
        $this->addSql('ALTER TABLE tic_tac_toe_move ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE tic_tac_toe_move ALTER move_answer TYPE VARCHAR(255)');
    }
}
