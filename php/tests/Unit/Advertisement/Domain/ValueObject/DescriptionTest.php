<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisement\Domain\ValueObject;

use Demo\App\Advertisement\Domain\Exceptions\DescriptionEmptyException;
use Demo\App\Advertisement\Domain\Exceptions\DescriptionTooLongException;
use Demo\App\Advertisement\Domain\Model\ValueObject\Description;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DescriptionTest extends TestCase
{
    private const string DESCRIPTION = 'description';

    public function testShouldNotBeInstantiatedWithTheConstructor(): void
    {
        $class = new ReflectionClass(Description::class);
        $constructor = $class->getConstructor();

        self::assertTrue($constructor->isPrivate(), 'Constructor is not private');
    }

    public function testShouldCreateADescription()
    {
        $result = Description::build(self::DESCRIPTION);

        $description = $result->unwrap();
        self::assertInstanceOf(Description::class, $description);
        self::assertEquals(self::DESCRIPTION, $description->value());
    }

    public function testShouldReturnErrorResultWhenDescriptionHasMoreThan200Characters()
    {
        $randomString = str_repeat('a', 201);
        $result = Description::build($randomString);

        self::assertFalse($result->isSuccess());
        self::assertInstanceOf(DescriptionTooLongException::class, $result->exception());
        self::assertEquals('Description has more than 200 characters: Has 201 characters', $result->exception()->getMessage());
    }

    public function testShouldReturnErrorResultWhenDescriptionIsEmpty()
    {
        $result = Description::build('');

        self::assertFalse($result->isSuccess());
        self::assertInstanceOf(DescriptionEmptyException::class, $result->exception());
        self::assertEquals('Description empty', $result->exception()->getMessage());
    }
}
