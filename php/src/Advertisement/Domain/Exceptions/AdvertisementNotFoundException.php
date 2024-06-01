<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Exceptions;

use Demo\App\Common\Application\ApplicationBoundedContextException;

final class AdvertisementNotFoundException extends ApplicationBoundedContextException
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
