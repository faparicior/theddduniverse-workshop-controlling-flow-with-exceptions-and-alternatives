<?php
declare(strict_types=1);

namespace Demo\App\Common\Domain;

use Exception;
use Throwable;

abstract class DomainException extends Exception
{
    protected function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function build(string $message = '', int $code = 0, Throwable $previous = null): self
    {
        return new static($message, $code, $previous);
    }
}
