<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LicitationAnswer\LicitationAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LicitationAnswer>
 */
class LicitationAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicitationAnswer::class);
    }

    public function add(LicitationAnswer $licitationAnswer): void
    {
        $this->getEntityManager()->persist($licitationAnswer);
        $this->getEntityManager()->flush();
    }
}
