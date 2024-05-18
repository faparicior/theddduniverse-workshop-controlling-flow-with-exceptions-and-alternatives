<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisement\Domain\ValueObject;

use Demo\App\Advertisement\Domain\Exceptions\InvalidEmailException;
use Demo\App\Advertisement\Domain\Model\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    private const string EMAIL = 'email@test.com';
    private const string INVALID_EMAIL = 'emailtest.com';

    public function testShouldCreateAnEmail()
    {
        $email = new Email(self::EMAIL);
        $this->assertEquals(self::EMAIL, $email->value());
    }

    public function testShouldThrowAnExceptionWhenEmailIsInvalid()
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('Invalid email ' . self::INVALID_EMAIL);
        new Email(self::INVALID_EMAIL);
    }
}
