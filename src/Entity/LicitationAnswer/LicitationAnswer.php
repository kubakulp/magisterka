<?php

declare(strict_types=1);

namespace App\Entity\LicitationAnswer;

class LicitationAnswer
{
    private int $licitationGameId;
    private string $playerId;
    private int $moveNumber;
    private string $moveAnswer;
    private bool $moveWasCorrect;

    public function __construct(
        int $licitationGameId,
        string $playerId,
        int $moveNumber,
        string $moveAnswer,
        bool $moveWasCorrect
    ) {
        $this->licitationGameId = $licitationGameId;
        $this->playerId = $playerId;
        $this->moveNumber = $moveNumber;
        $this->moveAnswer = $moveAnswer;
        $this->moveWasCorrect = $moveWasCorrect;
    }

    public function getLicitationGameId(): int
    {
        return $this->licitationGameId;
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function getMoveNumber(): int
    {
        return $this->moveNumber;
    }

    public function getMoveAnswer(): string
    {
        return $this->moveAnswer;
    }

    public function getMoveWasCorrect(): bool
    {
        return $this->moveWasCorrect;
    }
}
