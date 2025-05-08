<?php

declare(strict_types=1);

namespace App\Entity\LicitationPlayer;

class LicitationPlayer
{
    private string $id;
    private int $licitationGameId;
    private bool $wasEliminated = false;
    private bool $playerWon = false;

    public function __construct(
        string $id,
        int $licitationGameId,
    ) {
        $this->id = $id;
        $this->licitationGameId = $licitationGameId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLicitationGameId(): int
    {
        return $this->licitationGameId;
    }

    public function wasEliminated(): bool
    {
        return $this->wasEliminated;
    }

    public function setWasEliminated(bool $wasEliminated): void
    {
        $this->wasEliminated = $wasEliminated;
    }

    public function playerWon(): bool
    {
        return $this->playerWon;
    }

    public function setPlayerWon(bool $playerWon): void
    {
        $this->playerWon = $playerWon;
    }
}
