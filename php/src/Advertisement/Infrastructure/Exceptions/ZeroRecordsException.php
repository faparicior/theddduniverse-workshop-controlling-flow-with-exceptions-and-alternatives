<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Infrastructure\Exceptions;

use Demo\App\Common\Infrastructure\InfrastructureBoundedContextException;

final class ZeroRecordsException extends InfrastructureBoundedContextException
{
    private const string NO_RECORDS_FOUND_MESSAGE = 'No records found';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(string $message = self::NO_RECORDS_FOUND_MESSAGE): self
    {
        return new self($message);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
