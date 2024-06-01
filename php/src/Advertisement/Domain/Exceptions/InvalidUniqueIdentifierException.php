<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Exceptions;

use Demo\App\Common\Domain\DomainBoundedContextException;

final class InvalidUniqueIdentifierException extends DomainBoundedContextException
{
    private const string INVALID_ID_FORMAT_WITH_ID_MESSAGE = 'Invalid unique identifier format for ';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function withId(string $id): self
    {
        return new self(self::INVALID_ID_FORMAT_WITH_ID_MESSAGE . $id);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
