<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Chemem\Bingo\Functional\Functors\Monads\Either;
use Demo\App\Advertisement\Domain\Exceptions\InvalidEmailException;

final readonly class Email
{
    private function __construct(private string $value) {}

    public static function build(string $value): Either
    {
        if (!self::validate($value)) {
            return Either::left(InvalidEmailException::withEmail($value));
        }

        return Either::right(new self($value));
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
