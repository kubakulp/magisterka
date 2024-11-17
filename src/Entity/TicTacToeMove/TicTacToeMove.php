<?php

declare(strict_types=1);

namespace App\Entity\TicTacToeMove;

use App\Core\Domain\UUID\Uuid;
use App\Core\Domain\UUID\UuidV7Identifier;

class TicTacToeMove
{
    private Uuid $id;
    private int $gameId;
    private string $playerId;
    private int $playerNumber;
    private int $moveCount;
    private string $moveAnswer;
    private int $moveNumber;
    private int $moveEvaluation;
    private bool $moveWasCorrect;
    private \DateTimeImmutable $createdAt;

    public function __construct(int $gameId, string $playerId, int $playerNumber, int $moveCount, string $moveAnswer, int $moveNumber, int $moveEvaluation, bool $moveWasCorrect)
    {
        $this->id = UuidV7Identifier::generateNew();
        $this->gameId = $gameId;
        $this->playerId = $playerId;
        $this->playerNumber = $playerNumber;
        $this->moveCount = $moveCount;
        $this->moveAnswer = $moveAnswer;
        $this->moveNumber = $moveNumber;
        $this->moveEvaluation = $moveEvaluation;
        $this->moveWasCorrect = $moveWasCorrect;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getPlayerNumber(): int
    {
        return $this->playerNumber;
    }

    public function setPlayerNumber(int $playerNumber): void
    {
        $this->playerNumber = $playerNumber;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function setGameId(int $gameId): void
    {
        $this->gameId = $gameId;
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function setPlayerId(string $playerId): void
    {
        $this->playerId = $playerId;
    }

    public function getMoveCount(): int
    {
        return $this->moveCount;
    }

    public function setMoveCount(int $moveCount): void
    {
        $this->moveCount = $moveCount;
    }

    public function getMoveAnswer(): string
    {
        return $this->moveAnswer;
    }

    public function setMoveAnswer(string $moveAnswer): void
    {
        $this->moveAnswer = $moveAnswer;
    }

    public function getMoveNumber(): int
    {
        return $this->moveNumber;
    }

    public function setMoveNumber(int $moveNumber): void
    {
        $this->moveNumber = $moveNumber;
    }

    public function getMoveEvaluation(): int
    {
        return $this->moveEvaluation;
    }

    public function setMoveEvaluation(int $moveEvaluation): void
    {
        $this->moveEvaluation = $moveEvaluation;
    }

    public function isMoveWasCorrect(): bool
    {
        return $this->moveWasCorrect;
    }

    public function setMoveWasCorrect(bool $moveWasCorrect): void
    {
        $this->moveWasCorrect = $moveWasCorrect;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
