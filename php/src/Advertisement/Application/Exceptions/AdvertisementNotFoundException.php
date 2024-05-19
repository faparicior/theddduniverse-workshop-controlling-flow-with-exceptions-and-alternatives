<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Exceptions;

use Demo\App\Common\Application\ApplicationException;
use Throwable;

final class AdvertisementNotFoundException extends ApplicationException
{
    private const string NOT_FOUND_MESSAGE = 'Advertisement not found';
    private const string NOT_FOUND_WITH_ID_MESSAGE = self::NOT_FOUND_MESSAGE . ' with ID: ';

    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(string $message = self::NOT_FOUND_MESSAGE, int $code = 404, Throwable $previous = null): self
    {
        return new self($message);
    }

    public static function withId(string $id): self
    {
        return new self(self::NOT_FOUND_WITH_ID_MESSAGE . $id);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
