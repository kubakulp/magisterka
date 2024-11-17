<?php

declare(strict_types=1);

namespace App\Core\Domain\UUID;

use InvalidArgumentException;

final class SystemId extends UuidV7Identifier
{
    const ID = '01907820-8153-7105-919b-cf9336d99f3e';

    public static function generateNew(): Uuid
    {
        return self::fromString(self::ID);
    }

    public static function fromString(string $uuid): Uuid
    {
        if (self::ID !== $uuid) {
            throw new InvalidArgumentException('You cannot create system id with other id');
        }

        return parent::fromString($uuid);
    }
}
