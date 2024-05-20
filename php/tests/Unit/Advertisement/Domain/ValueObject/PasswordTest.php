<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisement\Domain\ValueObject;

use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class PasswordTest extends TestCase
{
    private const string STRONG_ALGORITHM_PASSWORD = '$argon2i$v=19$m=65536,t=4,p=1$b0t4M3dsYTlzNXlEaFBJTQ$1qxVJUQMvov1pe8zDpB03bTRYARs08X8GPhEvHKXVAI';
    private const string MD5_ALGORITHM_PASSWORD = 'deb1536f480475f7d593219aa1afd74c';
    private const string PLAIN_PASSWORD = 'myPassword';

    public function testShouldNotBeInstantiatedWithTheConstructor(): void
    {
        $class = new ReflectionClass(Password::class);
        $constructor = $class->getConstructor();

        self::assertTrue($constructor->isPrivate(), 'Constructor is not private');
    }

    public function testShouldBeCreatedWithAStrongHash(): void
    {
        $result = Password::fromPlainPassword('plain-password');
        $password = $result->unwrap();

        self::assertInstanceOf(Password::class, $password);
        self::assertStringStartsWith('$argon2i$', $password->value());
    }

    public function testShouldBeCreatedWithEncryptedValueWithoutChangeTheOriginalHash(): void
    {
        $result = Password::fromEncryptedPassword(self::STRONG_ALGORITHM_PASSWORD);
        $strongPassword = $result->unwrap();
        self::assertInstanceOf(Password::class, $strongPassword);

        $result = Password::fromEncryptedPassword(self::MD5_ALGORITHM_PASSWORD);
        $weakPassword = $result->unwrap();
        self::assertInstanceOf(Password::class, $weakPassword);

        self::assertEquals(self::STRONG_ALGORITHM_PASSWORD, $strongPassword->value());
        self::assertEquals(self::MD5_ALGORITHM_PASSWORD, $weakPassword->value());
    }

    public function testShouldValidatePasswordsWithAStrongAlgorithm(): void
    {
        $result = Password::fromEncryptedPassword(self::STRONG_ALGORITHM_PASSWORD);
        $password = $result->unwrap();

        self::assertTrue($password->isValidatedWith(self::PLAIN_PASSWORD));
    }

    public function testShouldValidatePasswordsWithAWeakAlgorithm(): void
    {
        $result = Password::fromEncryptedPassword(self::MD5_ALGORITHM_PASSWORD);
        $password = $result->unwrap();

        self::assertTrue($password->isValidatedWith(self::PLAIN_PASSWORD));
    }
}
