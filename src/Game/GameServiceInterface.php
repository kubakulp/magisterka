<?php

namespace App\Game;

use App\Player\PlayerInterface;

interface GameServiceInterface
{
    public function startGame(): void;

    public function sendRules(): void;

    /**
     * @return PlayerInterface[]
     */
    public function getPlayers(): array;
}
