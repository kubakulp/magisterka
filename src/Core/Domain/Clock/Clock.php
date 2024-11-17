<?php

declare(strict_types=1);

namespace App\Core\Domain\Clock;

use DateTimeImmutable;

interface Clock
{
    public function currentDateTime(): DateTimeImmutable;
}
