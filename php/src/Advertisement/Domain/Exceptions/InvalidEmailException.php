<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;

final class InvalidEmailException extends DomainException
{
    private const string INVALID_EMAIL_FORMAT_MESSAGE = 'Invalid email format';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function withEmail(string $email): self
    {
        return new self(self::INVALID_EMAIL_FORMAT_MESSAGE . ' ' . $email);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}