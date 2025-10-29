<?php
namespace App\Doctrine\Type;


use App\ValueObject\Status;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class StatusType extends Type
{
    public const NAME = 'status';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL([
            'length' => 50,
            'nullable' => false,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof Status) {
            return $value;
        }

        return new Status($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            throw new InvalidArgumentException("Status cannot be null");
        }

        if (!$value instanceof Status) {
            throw new InvalidArgumentException("Expected Status object");
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