<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250308073538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE licitation_answer (licitation_game_id INT NOT NULL, player_id VARCHAR(255) NOT NULL, move_number INT NOT NULL, move_answer VARCHAR(255) NOT NULL, move_was_correct BOOLEAN NOT NULL, PRIMARY KEY(licitation_game_id, player_id, move_number))');
        $this->addSql('CREATE TABLE licitation_game (id SERIAL NOT NULL, who_won VARCHAR(255) DEFAULT NULL, prompt_type VARCHAR(255) NOT NULL, number_of_repetitions INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE licitation_item (item_number INT NOT NULL, licitation_game_id INT NOT NULL, value INT NOT NULL, PRIMARY KEY(item_number, licitation_game_id, value))');
        $this->addSql('CREATE TABLE licitation_move (licitation_game_id INT NOT NULL, player_id VARCHAR(255) NOT NULL, item_id INT NOT NULL, value INT NOT NULL, PRIMARY KEY(licitation_game_id, player_id, item_id))');
        $this->addSql('CREATE TABLE licitation_player (id VARCHAR(255) NOT NULL, licitation_game_id INT NOT NULL, PRIMARY KEY(id, licitation_game_id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE licitation_answer');
        $this->addSql('DROP TABLE licitation_game');
        $this->addSql('DROP TABLE licitation_item');
        $this->addSql('DROP TABLE licitation_move');
        $this->addSql('DROP TABLE licitation_player');
    }
}
