<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

final readonly class AdvertisementId
{
    public function __construct(private string $value)
    {
        if (!$this->validate($value)) {
            throw new \InvalidArgumentException('Invalid unique identifier');
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
