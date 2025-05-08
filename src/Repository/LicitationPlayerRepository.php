<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LicitationPlayer\LicitationPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LicitationPlayer>
 */
class LicitationPlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicitationPlayer::class);
    }

    public function add(LicitationPlayer $licitationPlayer): void
    {
        $this->getEntityManager()->persist($licitationPlayer);
        $this->getEntityManager()->flush();
    }

    public function setEliminated(int $gameId, string $modelId): void
    {
        $player = $this->findOneBy([
            'licitationGameId' => $gameId,
            'id' => $modelId
        ]);
        $player->setWasEliminated(true);
        $this->getEntityManager()->flush();
    }

    /**
     * @return LicitationPlayer[]
     */
    public function getPlayers(int $gameId): array
    {
        return $this->findBy(['licitationGameId' => $gameId]);
    }

    public function setWinner(int $gameId, string $modelId): void
    {
        $player = $this->findOneBy([
            'licitationGameId' => $gameId,
            'id' => $modelId
        ]);
        $player->setPlayerWon(true);
        $this->getEntityManager()->flush();
    }
}
