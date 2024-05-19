<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

final readonly class AdvertisementDate
{
    public function __construct(private \DateTime $value)
    {
    }

    public function value(): \DateTime
    {
        return $this->value;
    }
}
