<?php
namespace App\Tests\ValueObject;

use App\ValueObject\Status;
use PHPUnit\Framework\TestCase;
use App\Exception\InvalidOptionValueObjectException;

final class StatusTest extends TestCase
{
    public function testCanBeCreatedWithPending(): void
    {
        $status = new Status(Status::PENDING);
        $this->assertSame(Status::PENDING, $status->value());
        $this->assertSame(Status::PENDING, (string)$status);
    }

    public function testCanBeCreatedWithPurchased(): void
    {
        $status = new Status(Status::PURCHASED);
        $this->assertSame(Status::PURCHASED, $status->value());
        $this->assertSame(Status::PURCHASED, (string)$status);
    }

    public function testStaticPendingMethodReturnsPendingStatus(): void
    {
        $status = Status::pending();
        $this->assertInstanceOf(Status::class, $status);
        $this->assertSame(Status::PENDING, $status->value());
    }

    public function testStaticPurchasedMethodReturnsPurchasedStatus(): void
    {
        $status = Status::purchased();
        $this->assertInstanceOf(Status::class, $status);
        $this->assertSame(Status::PURCHASED, $status->value());
    }

    public function testThrowsExceptionOnInvalidStatus(): void
    {
        $this->expectException(InvalidOptionValueObjectException::class);
        $this->expectExceptionMessage('Invalid status: invalid_value');

        new Status('invalid_value');
    }
}