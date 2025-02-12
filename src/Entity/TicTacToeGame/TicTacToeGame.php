<?php

declare(strict_types=1);

namespace App\Entity\TicTacToeGame;

use App\Core\PromptType;

class TicTacToeGame
{
    private int $id;
    private string $firstPlayerId;
    private string $secondPlayerId;
    //0 - draw, 1 - first player win, 2 - second player win,
    // 3 - unresolved because first player error, 4 - unresolved because second player error
    private ?int $score;
    private PromptType $promptType;
    private int $numberOfRepetitions;

    public function __construct(string $firstPlayerId, string $secondPlayerId, ?int $score, PromptType $promptType, int $numberOfRepetitions)
    {
        $this->firstPlayerId = $firstPlayerId;
        $this->secondPlayerId = $secondPlayerId;
        $this->score = $score;
        $this->promptType = $promptType;
        $this->numberOfRepetitions = $numberOfRepetitions;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstPlayerId(): string
    {
        return $this->firstPlayerId;
    }

    public function getSecondPlayerId(): string
    {
        return $this->secondPlayerId;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    public function getPromptType(): PromptType
    {
        return $this->promptType;
    }

    public function getNumberOfRepetitions(): int
    {
        return $this->numberOfRepetitions;
    }
}
