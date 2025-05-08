<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LicitationGame\LicitationGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LicitationGame>
 */
class LicitationGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicitationGame::class);
    }

    public function add(LicitationGame $licitationGame): void
    {
        $this->getEntityManager()->persist($licitationGame);
        $this->getEntityManager()->flush();
    }
}
