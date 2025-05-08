<?php

declare(strict_types=1);

namespace App\Entity\LicitationItem;

class LicitationItem
{
    private int $itemNumber;
    private int $licitationGameId;
    private int $value;

    public function __construct(
        int $itemNumber,
        int $licitationGameId,
        int $value
    ) {
        $this->itemNumber = $itemNumber;
        $this->licitationGameId = $licitationGameId;
        $this->value = $value;
    }

    public function getLicitationGameId(): int
    {
        return $this->licitationGameId;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getItemNumber(): int
    {
        return $this->itemNumber;
    }
}
