<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

final readonly class Email
{
    public function __construct(private string $value)
    {
        if (!$this->validate($value)) {
            throw new \InvalidArgumentException('Invalid email format ' . $this->value);
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    private function validate(string $value): string|bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
