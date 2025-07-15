<?php
namespace App\Tests\ValueObject;

use App\ValueObject\Slug;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SlugTest extends TestCase
{
    public function testItCanBeCreatedAndSlugifiesText(): void
    {
        $slug = new Slug('Hello World!');

        $this->assertSame('hello-world', $slug->value());
        $this->assertSame('hello-world', (string) $slug);
    }

    public function testItThrowsExceptionIfSlugIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Slug cannot be empty.');

        new Slug('!!!'); // solo caracteres que se eliminan y dejan vacío
    }

    public function testEqualsReturnsTrueForSameSlug(): void
    {
        $slug1 = new Slug('Test Slug');
        $slug2 = new Slug('test-slug');

        $this->assertTrue($slug1->equals($slug2));
    }

    public function testEqualsReturnsFalseForDifferentSlug(): void
    {
        $slug1 = new Slug('Test Slug');
        $slug2 = new Slug('other-slug');

        $this->assertFalse($slug1->equals($slug2));
    }

    public function testLevenshteinDistanceCalculatesCorrectly(): void
    {
        $slug1 = new Slug('hello-world');
        $slug2 = new Slug('hallo-world');

        $distance = $slug1->levenshteinDistance($slug2);
        $this->assertEquals(1, $distance);
    }

    public function testSlugifyMethodWorksStatically(): void
    {
        $slug = Slug::slugify('This is a test!');

        $this->assertSame('this-is-a-test', $slug);

        $slugEmpty = Slug::slugify('!!!');
        $this->assertSame('', $slugEmpty);
    }

    public function testSlugTrimsDashes(): void
    {
        $slug = new Slug('---Hello---World---');
        $this->assertSame('hello-world', $slug->value());
    }
}