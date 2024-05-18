<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use InvalidArgumentException;

final readonly class Email
{
    public function __construct(private string $email)
    {
        if (!$this->validateEmail($email)) {
            throw new InvalidArgumentException('Invalid email');
        }
    }

    public function value(): string
    {
        return $this->email;
    }

    private function validateEmail(string $email): string|bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
