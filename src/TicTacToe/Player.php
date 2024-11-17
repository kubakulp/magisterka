<?php

namespace App\TicTacToe;

use App\AiChatModel\AiChatModelInterface;
use App\Player\PlayerInterface;

class Player implements PlayerInterface
{
    public function __construct(
        private readonly AiChatModelInterface $model
    ){
    }

    public function getModel(): AiChatModelInterface
    {
        return $this->model;
    }
}
