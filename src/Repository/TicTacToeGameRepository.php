<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TicTacToeGame\TicTacToeGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TicTacToeGame>
 */
class TicTacToeGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicTacToeGame::class);
    }

    public function add(TicTacToeGame $ticTacToeGame): void
    {
        $this->getEntityManager()->persist($ticTacToeGame);
        $this->getEntityManager()->flush();
    }

    public function findGameAndSetScore(string $gameId, int $score): void
    {
        $game = $this->find($gameId);
        $game->setScore($score);
        $this->getEntityManager()->flush();
    }
}
