<?php

namespace App\TicTacToe;

use App\Command\TicTacToeGameCommand;
use App\Repository\TicTacToeGameRepository;
use App\Repository\TicTacToeMoveRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TicTacToeGame
{
    public function __construct(
        private TicTacToeGameRepository $ticTacToeGameRepository,
        private TicTacToeMoveRepository $ticTacToeMoveRepository,
    ) {
    }

    public function __invoke(TicTacToeGameCommand $command)
    {
        $gameService = new TicTacToeGameService(
            [
                new Player($command->model1),
                new Player($command->model2),
            ],
            $this->ticTacToeGameRepository,
            $this->ticTacToeMoveRepository,
            $command->promptType,
            $command->numberOfRepeats,
        );

        $gameService->startGame();
        sleep(121);
        while($gameService->isGameOver() === false) {
            $gameService->nextMove();
            sleep(121);
        }
    }
}
