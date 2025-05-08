<?php

namespace App\Command;

use App\Core\LicitationPromptType;

readonly class LicitationGameCommand
{
    /**
     * @param int[] $items
     */
    public function __construct(
        public array $models,
        public array $items,
        public LicitationPromptType $promptType,
        public int $numberOfRepeats,
        public int $numberOfTokens,
    ) {
    }
}
