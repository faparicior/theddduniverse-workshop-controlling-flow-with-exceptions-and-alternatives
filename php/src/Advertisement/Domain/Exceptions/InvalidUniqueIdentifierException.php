<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;
use Throwable;

final class InvalidUniqueIdentifierException extends DomainException
{
    public const string INVALID_ID_FORMAT_MESSAGE = 'Invalid unique identifier format';
    public const string INVALID_ID_FORMAT_WITH_ID_MESSAGE = self::INVALID_ID_FORMAT_MESSAGE . ' for ';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(string $message = self::INVALID_ID_FORMAT_MESSAGE, int $code = 0, Throwable $previous = null): self
    {
        return new self($message);
    }

    public static function withId(string $id): self
    {
        return self::build(self::INVALID_ID_FORMAT_WITH_ID_MESSAGE . $id);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
