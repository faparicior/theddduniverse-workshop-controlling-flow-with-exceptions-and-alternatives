<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Demo\App\Advertisement\Domain\Errors\DescriptionEmptyError;
use Demo\App\Advertisement\Domain\Errors\DescriptionTooLongError;
use Demo\App\Common\Result;

final class Description
{
    private function __construct(private string $value) {}

    public static function build(string $value): Result
    {
        if (!self::validateMinLength($value)) {
            return DescriptionEmptyError::build();
        }

        if (!self::validateMaxLength($value)) {
            return DescriptionTooLongError::build($value);
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
