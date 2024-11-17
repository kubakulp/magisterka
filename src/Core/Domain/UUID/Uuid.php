<?php

declare(strict_types=1);

namespace App\Core\Domain\UUID;

use JsonSerializable;

interface Uuid extends JsonSerializable
{
    public static function fromString(string $uuid): Uuid;

    public static function generateNew(): Uuid;

    public function isEquals(?Uuid $secondUuid): bool;

    public function value(): string;

    public static function isValid(string $uuid): bool;
}
