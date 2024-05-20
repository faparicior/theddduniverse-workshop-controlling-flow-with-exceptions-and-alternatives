<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisement\Domain\ValueObject;

use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class AdvertisementIdTest extends TestCase
{
    private const string ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29';
    private const string INVALID_ID = '6fa00b21-2930-983e-b610-d6b0e5b19b29';

    public function testShouldNotBeInstantiatedWithTheConstructor(): void
    {
        $class = new ReflectionClass(AdvertisementId::class);
        $constructor = $class->getConstructor();

        self::assertTrue($constructor->isPrivate(), 'Constructor is not private');
    }

    public function testShouldCreateAnAdvertisementId()
    {
        $result = AdvertisementId::build(self::ID);
        self::assertTrue($result->isSuccess());

        $advertisementId = $result->unwrap();
        self::assertInstanceOf(AdvertisementId::class, $advertisementId);
        self::assertEquals(self::ID, $advertisementId->value());
    }

    public function testShouldReturnErrorResultWhenIdHasNotUuidV4Standards()
    {
        $result = AdvertisementId::build(self::INVALID_ID);

        self::assertFalse($result->isSuccess());
        self::assertEquals('Invalid unique identifier format for ' . self::INVALID_ID, $result->getError());
    }
}
