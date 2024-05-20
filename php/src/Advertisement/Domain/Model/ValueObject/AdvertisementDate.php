<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Demo\App\Common\Result;

final readonly class AdvertisementDate
{
    private function __construct(private \DateTime $value)
    {
    }

    public static function build(\DateTime $value): Result
    {
        return Result::success(new self($value));
    }

    public function value(): \DateTime
    {
        return $this->value;
    }
}
