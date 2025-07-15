<?php
namespace App\Tests\ValueOject;

use App\ValueObject\HexColor;
use PHPUnit\Framework\TestCase;
use App\Exception\InvalidOptionValueObjectException;

class HexColorTest extends TestCase
{
    public function testItCanBeCreatedWithValid6DigitHexColor(): void
    {
        $hex = new HexColor('#a1b2c3');
        $this->assertSame('#A1B2C3', $hex->value());
    }

    public function testItCanBeCreatedWithValid8DigitHexColor(): void
    {
        $hex = new HexColor('#a1b2c3ff');
        $this->assertSame('#A1B2C3FF', $hex->value());
    }

    public function testItThrowsExceptionWithInvalidHexColors(): void
    {
        $invalidColors = [
            '123456',       // sin #
            '#12345',       // longitud incorrecta
            '#1234567',     // longitud incorrecta
            '#123456789',   // longitud incorrecta
            '#GHIJKL',      // caracteres no hex
            '#12345Z',      // caracteres no hex
            '',             // vacío
            '#',            // solo #
            '#12',          // demasiado corto
            '#1234567890',  // demasiado largo
        ];

        foreach ($invalidColors as $invalidColor) {
            try {
                new HexColor($invalidColor);
                $this->fail("Expected exception not thrown for color: {$invalidColor}");
            } catch (InvalidOptionValueObjectException $e) {
                $this->assertStringContainsString('Formato color inválido', $e->getMessage());
            }
        }
    }
}