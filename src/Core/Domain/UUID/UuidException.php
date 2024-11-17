<?php

declare(strict_types=1);

namespace App\Core\Domain\UUID;

use Exception;

class UuidException extends Exception
{
    public static function invalidUuidValue(string $uuid): self
    {
        return new self(sprintf('Invalid UUID value: "%s"', $uuid));
    }
}
