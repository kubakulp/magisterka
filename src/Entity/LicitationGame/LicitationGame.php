<?php

declare(strict_types=1);

namespace App\Entity\LicitationGame;

use App\Core\LicitationPromptType;

class LicitationGame
{
    private int $id;
    private LicitationPromptType $promptType;
    private int $numberOfRepetitions;
    private int $numberOfTokens;

    public function __construct(LicitationPromptType $promptType, int $numberOfRepetitions, int $numberOfTokens)
    {
        $this->promptType = $promptType;
        $this->numberOfRepetitions = $numberOfRepetitions;
        $this->numberOfTokens = $numberOfTokens;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPromptType(): LicitationPromptType
    {
        return $this->promptType;
    }

    public function getNumberOfRepetitions(): int
    {
        return $this->numberOfRepetitions;
    }
}
