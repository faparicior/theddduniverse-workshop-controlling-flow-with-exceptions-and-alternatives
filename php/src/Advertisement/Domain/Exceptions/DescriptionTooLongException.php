<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;
use Throwable;

final class DescriptionTooLongException extends DomainException
{
    public const string DESCRIPTION_TOO_LONG_MESSAGE = 'Description has more than 200 characters';
    public const string DESCRIPTION_TOO_LONG_WITH_COUNTER_MESSAGE = self::DESCRIPTION_TOO_LONG_MESSAGE . ': Has %s characters';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(string $message = self::DESCRIPTION_TOO_LONG_MESSAGE, int $code = 0, Throwable $previous = null): self
    {
        return new self($message);
    }

    public static function withLongitudeMessage(string $description): self
    {
        return self::build(sprintf(self::DESCRIPTION_TOO_LONG_WITH_COUNTER_MESSAGE, mb_strlen($description)));
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
