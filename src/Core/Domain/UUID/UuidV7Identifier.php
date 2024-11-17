<?php

declare(strict_types=1);

namespace App\Core\Domain\UUID;

use Symfony\Component\Uid\UuidV6;
use Symfony\Component\Uid\UuidV7;
use Throwable;

class UuidV7Identifier implements Uuid
{
    private UuidV7 $v7;

    private function __construct(UuidV7 $uuid)
    {
        $this->v7 = $uuid;
    }

    public static function fromString(string $uuid): Uuid
    {
        try {
            return new self(UuidV7::fromString($uuid));
        } catch (Throwable) {
            throw UuidException::invalidUuidValue($uuid);
        }
    }

    public static function generateNew(): Uuid
    {
        return new self(UuidV7::v7());
    }

    public function isEquals(UuidV7Identifier|Uuid|null $secondUuid): bool
    {
        return $secondUuid && $this->v7->__toString() === $secondUuid->value();
    }

    public function value(): string
    {
        return $this->v7->__toString();
    }

    public function jsonSerialize(): string
    {
        return $this->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public static function isValid(string $uuid): bool
    {
        return UuidV6::isValid($uuid);
    }
}
