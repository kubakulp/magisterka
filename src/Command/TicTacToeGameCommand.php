<?php

namespace App\Command;

use App\AiChatModel\AiChatModelInterface;
use App\Core\PromptType;

readonly class TicTacToeGameCommand
{
    public function __construct(
        public AiChatModelInterface $model1,
        public AiChatModelInterface $model2,
        public PromptType $promptType,
        public int $numberOfRepeats,
    ) {
    }
}
