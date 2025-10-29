<?php
namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Circle;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCanSetAndGetEmail(): void
    {
        $user = new User();
        $this->assertNull($user->getEmail());

        $user->setEmail('test@example.com');
        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('test@example.com', $user->getUserIdentifier());
    }

    public function testRolesAlwaysIncludeRoleUser(): void
    {
        $user = new User();
        $this->assertContains('ROLE_USER', $user->getRoles());

        $user->setRoles(['ROLE_ADMIN']);
        $roles = $user->getRoles();

        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
        $this->assertCount(2, $roles);
    }

    public function testCanSetAndGetPassword(): void
    {
        $user = new User();
        $this->assertNull($user->getPassword());

        $user->setPassword('hashed_password');
        $this->assertSame('hashed_password', $user->getPassword());
    }

    public function testCanSetAndGetName(): void
    {
        $user = new User();
        $this->assertNull($user->getName());

        $user->setName('John Doe');
        $this->assertSame('John Doe', $user->getName());
    }

    public function testCirclesCollectionInitiallyEmpty(): void
    {
        $user = new User();
        $this->assertCount(0, $user->getCircles());
    }

    public function testCanAddAndRemoveCircles(): void
    {
        $user = new User();
        $circle = new Circle();

        $this->assertCount(0, $user->getCircles());

        $user->addCircle($circle);
        $this->assertCount(1, $user->getCircles());
        $this->assertTrue($user->getCircles()->contains($circle));

        $user->removeCircle($circle);
        $this->assertCount(0, $user->getCircles());
    }

    public function testResetPasswordTokenAndExpiresAt(): void
    {
        $user = new User();

        $this->assertNull($user->getResetPasswordToken());
        $this->assertNull($user->getResetPasswordExpiresAt());

        $user->setResetPasswordToken('token123');
        $this->assertSame('token123', $user->getResetPasswordToken());

        $now = new \DateTimeImmutable();
        $user->setResetPasswordExpiresAt($now);
        $this->assertSame($now, $user->getResetPasswordExpiresAt());
    }

    public function testSerializeHashesPassword(): void
    {
        $user = new User();
        $user->setPassword('secret_password');

        $serialized = $user->__serialize();

        $this->assertArrayHasKey("\0App\Entity\User\0password", $serialized);
        $this->assertSame(
            hash('crc32c', 'secret_password'),
            $serialized["\0App\Entity\User\0password"]
        );
    }

    public function testEraseCredentialsIsNoop(): void
    {
        $user = new User();
        $this->expectNotToPerformAssertions();
        $user->eraseCredentials();
    }
}