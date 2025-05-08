<?php

declare(strict_types=1);

namespace App\Entity\LicitationMove;

class LicitationMove
{
    private int $licitationGameId;
    private string $playerId;
    private int $itemId;
    private int $value;

    public function __construct(
        int $licitationGameId,
        string $playerId,
        int $itemId,
        int $value
    ) {
        $this->licitationGameId = $licitationGameId;
        $this->playerId = $playerId;
        $this->itemId = $itemId;
        $this->value = $value;
    }

    public function getLicitationGameId(): int
    {
        return $this->licitationGameId;
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
