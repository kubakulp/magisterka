<?php

namespace App\TicTacToe;

use App\Core\PromptType;
use App\Entity\TicTacToeGame\TicTacToeGame;
use App\Entity\TicTacToeMove\TicTacToeMove;
use App\Game\GameServiceInterface;
use App\Prompt\TicTacToe\FirstPlayerMovePrompt;
use App\Prompt\TicTacToe\RulesPrompt;
use App\Prompt\TicTacToe\SecondPlayerMovePrompt;
use App\Repository\TicTacToeGameRepository;
use App\Repository\TicTacToeMoveRepository;
use App\Service\TicTacToeMoveEvaluationService;

class TicTacToeGameService implements GameServiceInterface
{
    private bool $isGameOver = false;
    /**
     * @var Player[] $players
     * @var int[] $moves
     */
    public function __construct(
        private readonly array $players,
        private TicTacToeGameRepository $ticTacToeGameRepository,
        private TicTacToeMoveRepository $ticTacToeMoveRepository,
        private PromptType $promptType,
        public int $numberOfRepeats,
        private int $moveCount = 0,
        private Board $board = new Board(),
        public int $gameId = 0,
        public array $moves = [],
    )
    {
    }

    public function startGame(): void
    {
        $this->sendRules();
        $game = new TicTacToeGame(
            $this->players[0]->getModel()->getIdentifier(),
            $this->players[1]->getModel()->getIdentifier(),
            null,
            $this->promptType,
            $this->numberOfRepeats
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
        if($this->promptType == PromptType::Normal)
        {
            $this->getPlayers()[0]->getModel()->sendMessage(RulesPrompt::getPrompt());
            $this->getPlayers()[1]->getModel()->sendMessage(RulesPrompt::getPrompt());
        }

        if($this->promptType == PromptType::Best_move_only)
        {
            $this->getPlayers()[0]->getModel()->sendMessage(RulesPrompt::getBestMoveRulesPrompt());
            $this->getPlayers()[1]->getModel()->sendMessage(RulesPrompt::getBestMoveRulesPrompt());
        }
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
            if($this->promptType == PromptType::Best_move_only)
            {
                $answer = $this->getPlayers()[0]->getModel()->sendMessage(FirstPlayerMovePrompt::getBestMovePrompt($this->board));
            } else {
                $answer = $this->getPlayers()[0]->getModel()->sendMessage(FirstPlayerMovePrompt::getPrompt($this->board));
            }
            $value = 'O';
            $boardCellFromAnswer = $this->getBoardCellFromAnswer($answer);
            if($boardCellFromAnswer == -1)
            {
                $moveWasCorrect = false;
            } else {
                $moveWasCorrect = !($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '');
                if($moveWasCorrect)
                {
                    $this->moves[] = $boardCellFromAnswer;
                }
            }
            $this->ticTacToeMoveRepository->add(new TicTacToeMove(
                $this->gameId,
                $this->players[0]->getModel()->getIdentifier(),
                1,
                $this->moveCount,
                $answer,
                $boardCellFromAnswer,
                TicTacToeMoveEvaluationService::calculateEvaluationMove($this->moves, $moveWasCorrect),
                $moveWasCorrect,
            ));
        } else {
            if($this->promptType == PromptType::Best_move_only)
            {
                $answer = $this->getPlayers()[1]->getModel()->sendMessage(SecondPlayerMovePrompt::getBestMovePrompt($this->board));
            } else {
                $answer = $this->getPlayers()[1]->getModel()->sendMessage(SecondPlayerMovePrompt::getPrompt($this->board));
            }
            $value = 'X';
            $boardCellFromAnswer = $this->getBoardCellFromAnswer($answer);
            if($boardCellFromAnswer == -1)
            {
                $moveWasCorrect = false;
            } else {
                $moveWasCorrect = !($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '');
                if($moveWasCorrect)
                {
                    $this->moves[] = $boardCellFromAnswer;
                }
            }
            $this->ticTacToeMoveRepository->add(new TicTacToeMove(
                $this->gameId,
                $this->players[1]->getModel()->getIdentifier(),
                2,
                $this->moveCount,
                $answer,
                $boardCellFromAnswer,
                TicTacToeMoveEvaluationService::calculateEvaluationMove($this->moves, $moveWasCorrect),
                $moveWasCorrect
            ));
        }

        while(!$moveWasCorrect)
        {
            if($boardCellFromAnswer==-1)
            {
                if ($this->moveCount%2==1) {
                    $answer = $this->getPlayers()[0]->getModel()->sendMessage('Invalid cell value. Please choose cell from 0 to 8.');
                    $boardCellFromAnswer = $this->getBoardCellFromAnswer($answer);
                    if($boardCellFromAnswer == -1)
                    {
                        $moveWasCorrect = false;
                    } else {
                        $moveWasCorrect = !($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '');
                        if($moveWasCorrect)
                        {
                            $this->moves[] = $boardCellFromAnswer;
                        }
                    }
                    $this->ticTacToeMoveRepository->add(new TicTacToeMove(
                        $this->gameId,
                        $this->players[0]->getModel()->getIdentifier(),
                        1,
                        $this->moveCount,
                        $answer,
                        $boardCellFromAnswer,
                        TicTacToeMoveEvaluationService::calculateEvaluationMove($this->moves, $moveWasCorrect),
                        $moveWasCorrect
                    ));
                }

                if ($this->moveCount%2==0) {
                    $answer = $this->getPlayers()[1]->getModel()->sendMessage('Invalid cell value. Please choose cell from 0 to 8.');
                    $boardCellFromAnswer = $this->getBoardCellFromAnswer($answer);
                    if($boardCellFromAnswer == -1)
                    {
                        $moveWasCorrect = false;
                    } else {
                        $moveWasCorrect = !($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '');
                        if($moveWasCorrect)
                        {
                            $this->moves[] = $boardCellFromAnswer;
                        }
                    }
                    $this->ticTacToeMoveRepository->add(new TicTacToeMove(
                        $this->gameId,
                        $this->players[1]->getModel()->getIdentifier(),
                        2,
                        $this->moveCount,
                        $answer,
                        $boardCellFromAnswer,
                        TicTacToeMoveEvaluationService::calculateEvaluationMove($this->moves, $moveWasCorrect),
                        $moveWasCorrect
                    ));
                }
            } else if ($this->moveCount%2==1) {
                $answer = $this->getPlayers()[0]->getModel()->sendMessage('This cell is already taken. Please choose another one from 0 to 8.');
                $boardCellFromAnswer = $this->getBoardCellFromAnswer($answer);
                if($boardCellFromAnswer == -1)
                {
                    $moveWasCorrect = false;
                } else {
                    $moveWasCorrect = !($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '');
                    if($moveWasCorrect)
                    {
                        $this->moves[] = $boardCellFromAnswer;
                    }
                }
                $this->ticTacToeMoveRepository->add(new TicTacToeMove(
                    $this->gameId,
                    $this->players[0]->getModel()->getIdentifier(),
                    1,
                    $this->moveCount,
                    $answer,
                    $boardCellFromAnswer,
                    TicTacToeMoveEvaluationService::calculateEvaluationMove($this->moves, $moveWasCorrect),
                    $moveWasCorrect
                ));
            } else if ($this->moveCount%2==0) {
                $answer = $this->getPlayers()[1]->getModel()->sendMessage('This cell is already taken. Please choose another one from 0 to 8.');
                $boardCellFromAnswer = $this->getBoardCellFromAnswer($answer);
                if($boardCellFromAnswer == -1)
                {
                    $moveWasCorrect = false;
                } else {
                    $moveWasCorrect = !($this->board->board[$this->getBoardCellFromAnswer($answer)] !== '');
                    if($moveWasCorrect)
                    {
                        $this->moves[] = $boardCellFromAnswer;
                    }
                }
                $this->ticTacToeMoveRepository->add(new TicTacToeMove(
                    $this->gameId,
                    $this->players[1]->getModel()->getIdentifier(),
                    2,
                    $this->moveCount,
                    $answer,
                    $boardCellFromAnswer,
                    TicTacToeMoveEvaluationService::calculateEvaluationMove($this->moves, $moveWasCorrect),
                    $moveWasCorrect
                ));
            }

            $temp++;
            if($temp==$this->numberOfRepeats)
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
        $this->board->setCellValue($boardCellFromAnswer, $value);

        if($this->checkIfGameIsOver())
        {
            $this->win($value);
        } else if($this->moveCount==9)
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
        $this->ticTacToeGameRepository->findGameAndSetScore($this->gameId, 0);
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
            $number = (int)$matches[0];
            if ($number >= 0 && $number <= 8) {
                return $number;
            }
        }
        return -1;
    }
}
