<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LicitationItem\LicitationItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LicitationItem>
 */
class LicitationItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicitationItem::class);
    }

    public function add(LicitationItem $licitationItem): void
    {
        $this->getEntityManager()->persist($licitationItem);
        $this->getEntityManager()->flush();
    }
}
