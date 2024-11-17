<?php

namespace App\TicTacToe\Command;

use App\AiChatModel\AiChatModelInterface;

readonly class TicTacToeGameCommand
{
    public function __construct(
        public AiChatModelInterface $model1,
        public AiChatModelInterface $model2,
    ) {
    }
}
