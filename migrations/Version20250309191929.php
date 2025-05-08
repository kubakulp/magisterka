<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250309191929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE licitation_game DROP who_won');
        $this->addSql('ALTER TABLE licitation_player ADD player_won BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE licitation_game ADD who_won VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE licitation_player DROP player_won');
    }
}
