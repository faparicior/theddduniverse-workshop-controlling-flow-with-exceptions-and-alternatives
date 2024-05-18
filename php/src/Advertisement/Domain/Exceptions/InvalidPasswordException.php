<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;
use Throwable;

final class InvalidPasswordException extends DomainException
{
    public const string INVALID_PASSWORD_MESSAGE = 'Invalid password';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(string $message = self::INVALID_PASSWORD_MESSAGE, int $code = 0, Throwable $previous = null): self
    {
        return new self($message);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
