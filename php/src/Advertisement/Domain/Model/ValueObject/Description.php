<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Demo\App\Advertisement\Domain\Exceptions\DescriptionEmptyException;
use Demo\App\Advertisement\Domain\Exceptions\DescriptionTooLongException;
use Demo\App\Common\Result;

final class Description
{
    private function __construct(private string $value) {}

    public static function build(string $value): Result
    {
        if (!self::validateMinLength($value)) {
            return Result::failure(DescriptionEmptyException::build());
        }

        if (!self::validateMaxLength($value)) {
            return Result::failure(DescriptionTooLongException::withLongitudeMessage($value));
        }

        return Result::success(new self($value));
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
