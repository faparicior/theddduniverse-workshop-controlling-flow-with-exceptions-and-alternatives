<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;
use Throwable;

final class AdvertisementNotFoundException extends DomainException
{
    private const string NOT_FOUND_MESSAGE = 'Advertisement not found';

    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(string $message = self::NOT_FOUND_MESSAGE, int $code = 0, Throwable $previous = null): self
    {
        return new self($message);
    }


    public function message(): string
    {
        return $this->getMessage();
    }
}