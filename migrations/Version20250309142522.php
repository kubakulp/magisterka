<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250309142522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE licitation_player ADD was_eliminated BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE licitation_player DROP was_eliminated');
    }
}
