<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241117205129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add prompt type to tic tac toe game';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tic_tac_toe_game ADD prompt_type VARCHAR(255) NOT NULL DEFAULT \'\'');
        $this->addSql('ALTER TABLE tic_tac_toe_move ALTER id TYPE UUID');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tic_tac_toe_game DROP prompt_type');
        $this->addSql('ALTER TABLE tic_tac_toe_move ALTER id TYPE UUID');
    }
}
