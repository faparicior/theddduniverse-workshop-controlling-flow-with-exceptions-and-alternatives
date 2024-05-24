<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisement\Domain\ValueObject;

use Demo\App\Advertisement\Domain\Exceptions\InvalidEmailException;
use Demo\App\Advertisement\Domain\Model\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class EmailTest extends TestCase
{
    private const string EMAIL = 'email@test.com';
    private const string INVALID_EMAIL = 'emailtest.com';

    public function testShouldNotBeInstantiatedWithTheConstructor(): void
    {
        $class = new ReflectionClass(Email::class);
        $constructor = $class->getConstructor();

        self::assertTrue($constructor->isPrivate(), 'Constructor is not private');
    }

    public function testShouldCreateAnEmail()
    {
        $result = Email::build(self::EMAIL);

        $email = $result->unwrap();

        self::assertInstanceOf(Email::class, $email);
        self::assertEquals(self::EMAIL, $email->value());
    }

    public function testShouldReturnErrorResultWhenEmailIsInvalid()
    {
        $result = Email::build(self::INVALID_EMAIL);

        self::assertFalse($result->isSuccess());
        self::assertInstanceOf(InvalidEmailException::class, $result->exception());
        self::assertEquals('Invalid email format ' . self::INVALID_EMAIL, $result->exception()->getMessage());
    }
}
