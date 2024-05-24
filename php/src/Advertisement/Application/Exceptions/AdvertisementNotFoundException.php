<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Exceptions;

use Demo\App\Common\Application\ApplicationException;
use Throwable;

final class AdvertisementNotFoundException extends ApplicationException
{
    private const string NOT_FOUND_WITH_ID_MESSAGE = 'Advertisement not found with ID: ';
    private const int NOT_FOUND_ERROR_CODE = 404;

    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct($message, self::NOT_FOUND_ERROR_CODE);
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