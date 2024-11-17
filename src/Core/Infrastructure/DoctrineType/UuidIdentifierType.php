<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\DoctrineType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use App\Core\Domain\UUID\Uuid;
use App\Core\Domain\UUID\UuidV7Identifier;

class UuidIdentifierType extends Type
{
    public function getName(): string
    {
        return 'uuid';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if ($this->hasNativeGuidType($platform)) {
            return $platform->getGuidTypeDeclarationSQL($column);
        }

        return $platform->getBinaryTypeDeclarationSQL([
            'length' => 16,
            'fixed' => true,
        ]);
    }

    /**
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
    {
        return $value ? UuidV7Identifier::fromString($value) : null;
    }

    /**
     * @param  Uuid|null $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value?->value();
    }

    private function hasNativeGuidType(AbstractPlatform $platform): bool
    {
        return $platform->getGuidTypeDeclarationSQL([]) !== $platform->getStringTypeDeclarationSQL(['fixed' => true, 'length' => 36]);
    }
}
