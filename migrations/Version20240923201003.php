<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240923201003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tic_tac_toe_move (id UUID NOT NULL, game_id INT NOT NULL, player_id UUID NOT NULL, player_number INT NOT NULL, move_count INT NOT NULL, move_answer VARCHAR(255) NOT NULL, move_number INT NOT NULL, move_evaluation INT NOT NULL, move_was_correct BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tic_tac_toe_move');
    }
}
