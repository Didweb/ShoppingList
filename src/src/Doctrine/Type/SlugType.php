<?php
namespace App\Doctrine\Type;

use App\ValueObject\Slug;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class SlugType extends Type
{
    public const NAME = 'slug';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL([
            'length' => 50,
            'nullable' => false,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof Slug) {
            return $value;
        }

        return new Slug($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            throw new InvalidArgumentException("Slug no puede ser null");
        }

        if (!$value instanceof Slug) {
            throw new InvalidArgumentException("Expected Slug object");
        }

        return $value->value();
    }

    public function getName()
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}