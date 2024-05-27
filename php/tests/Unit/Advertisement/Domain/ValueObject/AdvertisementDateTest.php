<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisement\Domain\ValueObject;

use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementDate;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class AdvertisementDateTest extends TestCase
{
    private const string ADVERTISEMENT_CREATION_DATE = '2024-02-03 13:30:23';

    public function testShouldNotBeInstantiatedWithTheConstructor(): void
    {
        $class = new ReflectionClass(AdvertisementDate::class);
        $constructor = $class->getConstructor();

        self::assertTrue($constructor->isPrivate(), 'Constructor is not private');
    }

    public function testShouldBeCreatedWithADate(): void
    {
        $result = AdvertisementDate::build(new \DateTime(self::ADVERTISEMENT_CREATION_DATE));
        $advertisementDate = $result->getOrThrow();

        self::assertEquals(new \DateTime(self::ADVERTISEMENT_CREATION_DATE), $advertisementDate->value());
    }
}
