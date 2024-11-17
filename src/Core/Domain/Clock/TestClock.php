<?php

declare(strict_types=1);

namespace App\Core\Domain\Clock;

use DateTimeImmutable;

final class TestClock implements Clock
{
    private ?DateTimeImmutable $currentDateTime = null;

    public function currentDateTime(): DateTimeImmutable
    {
        return $this->currentDateTime ?: new DateTimeImmutable();
    }

    public function setCurrentDateTime(?DateTimeImmutable $currentDateTime): void
    {
        $this->currentDateTime = $currentDateTime;
    }

    public function reset(): void
    {
        $this->currentDateTime = null;
    }
}
