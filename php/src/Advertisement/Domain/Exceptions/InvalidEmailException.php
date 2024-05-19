<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;
use Throwable;

final class InvalidEmailException extends DomainException
{
    public const string INVALID_EMAIL_FORMAT_MESSAGE = 'Invalid email format';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(string $message = self::INVALID_EMAIL_FORMAT_MESSAGE, int $code = 0, Throwable $previous = null): self
    {
        return new self($message);
    }

    public static function withEmail(string $email): self
    {
        return self::build(self::INVALID_EMAIL_FORMAT_MESSAGE . ' ' . $email);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
