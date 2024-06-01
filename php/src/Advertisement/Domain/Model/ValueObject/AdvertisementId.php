<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Chemem\Bingo\Functional\Functors\Monads\Either;
use Demo\App\Advertisement\Domain\Exceptions\InvalidUniqueIdentifierException;

final readonly class AdvertisementId
{
    private function __construct(private string $value) {}

    public static function build(string $value): Either
    {
        if (!self::validate($value)) {
            return Either::left(InvalidUniqueIdentifierException::withId($value));
        }

        return Either::right(new self($value));
    }

    public function value(): string
    {
        return $this->value;
    }

    private static function validate(string $value): bool
    {
        return preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $value) != 0;
    }
}
