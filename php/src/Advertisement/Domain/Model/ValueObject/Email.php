<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Demo\App\Advertisement\Domain\Exceptions\InvalidEmailException;

final readonly class Email
{
    /**
     * @throws InvalidEmailException
     */
    public function __construct(private string $email)
    {
        if (!$this->validateEmail($email)) {
            throw InvalidEmailException::withEmail($this->email);
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
