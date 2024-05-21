<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Demo\App\Advertisement\Domain\Errors\InvalidEmailFormatError;
use Demo\App\Common\Result;

final readonly class Email
{
    private function __construct(private string $value) {}

    public static function build(string $value): Result
    {
        if (!self::validate($value)) {
            return InvalidEmailFormatError::build($value);
        }

        return Result::success(new self($value));
    }

    public function value(): string
    {
        return $this->value;
    }

    private static function validate(string $value): string|bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
