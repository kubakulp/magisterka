<?php

namespace App\Licitation;

use App\Command\LicitationGameCommand;
use App\Repository\LicitationAnswerRepository;
use App\Repository\LicitationGameRepository;
use App\Repository\LicitationItemRepository;
use App\Repository\LicitationMoveRepository;
use App\Repository\LicitationPlayerRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class LicitationGame
{
    public function __construct(
        private LicitationGameRepository   $licitationGameRepository,
        private LicitationPlayerRepository $licitationPlayerRepository,
        private LicitationItemRepository   $licitationItemRepository,
        private LicitationAnswerRepository $licitationAnswerRepository,
        private LicitationMoveRepository   $licitationMoveRepository,
    ) {
    }

    public function __invoke(LicitationGameCommand $command)
    {
        $gameService = new LicitationGameService(
            $command->models,
            $command->items,
            $this->licitationGameRepository,
            $this->licitationAnswerRepository,
            $this->licitationItemRepository,
            $this->licitationMoveRepository,
            $this->licitationPlayerRepository,
            $command->promptType,
            $command->numberOfRepeats,
            $command->numberOfTokens,
        );

        $gameService->startGame();
        sleep(60);
    }
}
