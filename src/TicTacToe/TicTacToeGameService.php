<?php

namespace App\TicTacToe;

use App\Entity\TicTacToeGame\TicTacToeGame;
use App\Entity\TicTacToeMove\TicTacToeMove;
use App\Game\GameServiceInterface;
use App\Prompt\TicTacToe\FirstPlayerMovePrompt;
use App\Prompt\TicTacToe\RulesPrompt;
use App\Prompt\TicTacToe\SecondPlayerMovePrompt;
use App\Repository\TicTacToeGameRepository;
use App\Repository\TicTacToeMoveRepository;

class TicTacToeGameService implements GameServiceInterface
{
    private bool $isGameOver = false;
    /**
     * @var Player[] $players
     */
    public function __construct(
        private readonly array $players,
        private TicTacToeGameRepository $ticTacToeGameRepository,
        private TicTacToeMoveRepository $ticTacToeMoveRepository,
        private int $moveCount = 0,
        private Board $board = new Board(),
        public int $gameId = 0,
    )
    {
    }

    public function startGame(): void
    {
        $this->sendRules();
        $game = new TicTacToeGame(
            $this->players[0]->getModel()->getIdentifier(),
            $this->players[1]->getModel()->getIdentifier(),
            null
        );

        $this->ticTacToeGameRepository->add($game);
        $this->gameId = $game->getId();
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getMoveCount(): int
    {
        return $this->moveCount;
    }

    public function sendRules(): void
    {
        $this->getPlayers()[0]->getModel()->sendMessage(RulesPrompt::getPrompt());
        $this->getPlayers()[1]->getModel()->sendMessage(RulesPrompt::getPrompt());
    }

    public function isGameOver(): bool
    {
        return $this->isGameOver;
    }

    public function nextMove(): void
    {
        $this->moveCount++;
        $temp = 0;

        if ($this->moveCount%2==1)
        {
            $answer = $this->getPlayers()[0]->getModel()->sendMessage(FirstPlayerMovePrompt::getPrompt($this->board));
            $value = 'O';
            $this->ticTacToeMoveRepository->add(new TicTacToeMove(
                $this->gameId,
                $this->players[0]->getModel()->getIdentifier(),
                1,
                $this->moveCount,
                $answer,
                $this->getBoardCellFromAnswer($answer),
                0,
                !($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '')
            ));
        } else {
            $answer = $this->getPlayers()[1]->getModel()->sendMessage(SecondPlayerMovePrompt::getPrompt($this->board));
            $value = 'X';
            $this->ticTacToeMoveRepository->add(new TicTacToeMove(
                $this->gameId,
                $this->players[1]->getModel()->getIdentifier(),
                2,
                $this->moveCount,
                $answer,
                $this->getBoardCellFromAnswer($answer),
                0,
                !($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '')
            ));
        }

        while($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '')
        {
            if ($this->moveCount%2==1) {
                $answer = $this->getPlayers()[0]->getModel()->sendMessage('This cell is already taken. Please choose another one from 0 to 8.');
                $this->ticTacToeMoveRepository->add(new TicTacToeMove(
                    $this->gameId,
                    $this->players[0]->getModel()->getIdentifier(),
                    1,
                    $this->moveCount,
                    $answer,
                    $this->getBoardCellFromAnswer($answer),
                    0,
                    !($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '')
                ));
            }
            if ($this->moveCount%2==0) {
                $answer = $this->getPlayers()[1]->getModel()->sendMessage('This cell is already taken. Please choose another one from 0 to 8.');
                $this->ticTacToeMoveRepository->add(new TicTacToeMove(
                    $this->gameId,
                    $this->players[1]->getModel()->getIdentifier(),
                    2,
                    $this->moveCount,
                    $answer,
                    $this->getBoardCellFromAnswer($answer),
                    0,
                    !($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '')
                ));
            }

            $temp++;
            if($temp==3)
            {
                if($value == 'O')
                {
                    $this->ticTacToeGameRepository->findGameAndSetScore($this->gameId, 3);
                    $this->isGameOver = true;
                }

                if($value == 'X')
                {
                    $this->ticTacToeGameRepository->findGameAndSetScore($this->gameId, 4);
                    $this->isGameOver = true;
                }
                return;
            }
        }
        $this->board->setCellValue($this->getBoardCellFromAnswer($answer), $value);

        if($this->checkIfGameIsOver())
        {
            $this->win($value);
        }

        if($this->moveCount==9)
        {
            $this->draw();
        }
    }

    private function win(string $player): void
    {
        if($player == 'O')
        {
            $this->getPlayers()[0]->getModel()->sendMessage('You won!');
            $this->getPlayers()[1]->getModel()->sendMessage('You lost!');
            $this->ticTacToeGameRepository->findGameAndSetScore($this->gameId, 1);
            $this->isGameOver = true;
        }

        if($player == 'X')
        {
            $this->getPlayers()[1]->getModel()->sendMessage('You won!');
            $this->getPlayers()[0]->getModel()->sendMessage('You lost!');
            $this->ticTacToeGameRepository->findGameAndSetScore($this->gameId, 2);
            $this->isGameOver = true;
        }
    }

    private function draw(): void
    {
        $this->getPlayers()[0]->getModel()->sendMessage('Draw!');
        $this->getPlayers()[1]->getModel()->sendMessage('Draw!');
        $this->ticTacToeGameRepository->find($this->gameId)->setScore(0);
        $this->isGameOver = true;
    }

    private function checkOneLine(int $a, int $b, int $c): bool
    {
        return $this->board->board[$a] == $this->board->board[$b] && $this->board->board[$b] == $this->board->board[$c] && $this->board->board[$c] != '';
    }

    private function checkIfGameIsOver(): bool
    {
        return $this->checkOneLine(0, 1, 2) || $this->checkOneLine(3, 4, 5) || $this->checkOneLine(6, 7, 8) ||
            $this->checkOneLine(0, 3, 6) || $this->checkOneLine(1, 4, 7) || $this->checkOneLine(2, 5, 8) ||
            $this->checkOneLine(0, 4, 8) || $this->checkOneLine(2, 4, 6);
    }

    private function getBoardCellFromAnswer(string $answer): int
    {
        if (preg_match('/\d+/', $answer, $matches)) {
            return (int)$matches[0];
        }
        return 0;
    }
}
