<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Chemem\Bingo\Functional\Functors\Monads\Either;

final readonly class AdvertisementDate
{
    private function __construct(private \DateTime $value)
    {
    }

    public static function build(\DateTime $value): Either
    {
        return Either::right(new self($value));
    }

    public function value(): \DateTime
    {
        return $this->value;
    }
}
