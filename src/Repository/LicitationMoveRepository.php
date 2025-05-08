<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LicitationMove\LicitationMove;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LicitationMove>
 */
class LicitationMoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicitationMove::class);
    }

    public function add(LicitationMove $licitationMove): void
    {
        $this->getEntityManager()->persist($licitationMove);
        $this->getEntityManager()->flush();
    }

    /**
     * @return LicitationMove[]
     */
    public function getMoves(int $gameId, int $itemId): array
    {
        return $this->findBy([
            'licitationGameId' => $gameId,
            'itemId' => $itemId
        ]);
    }
}
