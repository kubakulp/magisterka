<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250111215456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tic_tac_toe_game ADD number_of_repetitions INT NOT NULL default 3');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tic_tac_toe_game DROP number_of_repetitions');
    }
}
