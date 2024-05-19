<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Demo\App\Advertisement\Domain\Exceptions\InvalidUniqueIdentifierException;

final readonly class AdvertisementId
{
    /**
     * @throws InvalidUniqueIdentifierException
     */
    public function __construct(private string $value)
    {
        if (!$this->validate($value)) {
            throw InvalidUniqueIdentifierException::withId($this->value);
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    private function validate(string $value): bool
    {
        return preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $value) != 0;
    }
}
