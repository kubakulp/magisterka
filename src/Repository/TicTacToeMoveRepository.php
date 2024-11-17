<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TicTacToeMove\TicTacToeMove;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TicTacToeMove>
 */
class TicTacToeMoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicTacToeMove::class);
    }

    public function add(TicTacToeMove $ticTacToeMove): void
    {
        $this->getEntityManager()->persist($ticTacToeMove);
        $this->getEntityManager()->flush();
    }
}
