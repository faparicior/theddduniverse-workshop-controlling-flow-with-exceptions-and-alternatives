<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Exceptions;

use Demo\App\Common\Application\ApplicationBoundedContextException;
use Throwable;

final class InvalidPasswordException extends ApplicationBoundedContextException
{
    private const string INVALID_PASSWORD_MESSAGE = 'Invalid password';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(string $message = self::INVALID_PASSWORD_MESSAGE): self
    {
        return new self($message);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
