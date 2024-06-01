<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Chemem\Bingo\Functional\Functors\Monads\Either;
use Demo\App\Advertisement\Domain\Exceptions\DescriptionEmptyException;
use Demo\App\Advertisement\Domain\Exceptions\DescriptionTooLongException;

final class Description
{
    private function __construct(private string $value) {}

    public static function build(string $value): Either
    {
        if (!self::validateMinLength($value)) {
            return Either::left(DescriptionEmptyException::build());
        }

        if (!self::validateMaxLength($value)) {
            return Either::left(DescriptionTooLongException::withLongitudeMessage($value));
        }

        return Either::right(new self($value));
    }

    public function value(): string
    {
        return $this->value;
    }

    private static function validateMinLength(string $value): bool
    {
        return mb_strlen($value) > 0;
    }

    private static function validateMaxLength(string $value): bool
    {
        return mb_strlen($value) < 200;
    }
}
