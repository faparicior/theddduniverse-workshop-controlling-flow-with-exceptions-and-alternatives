<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Demo\App\Advertisement\Domain\Errors\DescriptionErrors;
use Demo\App\Common\Result;

final class Description
{
    private function __construct(private string $value) {}

    public static function build(string $value): Result
    {
        if (!self::validateMinLength($value)) {
            return Result::failure(DescriptionErrors::DESCRIPTION_MIN_LENGTH_INVALID->getMessage());
        }

        if (!self::validateMaxLength($value)) {
            return Result::failure(sprintf(DescriptionErrors::DESCRIPTION_MAX_LENGTH_INVALID->getMessage(), mb_strlen($value)));
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
