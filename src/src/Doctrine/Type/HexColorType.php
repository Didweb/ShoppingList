<?php
namespace App\Doctrine\Type;

use App\ValueObject\HexColor;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;


final class HexColorType extends Type
{
    public const NAME = 'hex_color';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL([
            'length' => 9,
            'fixed' => true,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return new HexColor($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof HexColor) {
            throw new \InvalidArgumentException('Expected HexColor.');
        }

        return $value->value();
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}