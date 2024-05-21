<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Infrastructure\Errors;

use Demo\App\Common\ErrorsDictionary;

enum AdvertisementRepositoryErrors implements ErrorsDictionary
{
    case ADVERTISEMENT_NOT_FOUND;

    public function getMessage(): string
    {
        return match ($this) {
            self::ADVERTISEMENT_NOT_FOUND => 'Advertisement not found with ID: ',
        };
    }

    public function getCode(): string
    {
        return match ($this) {
            self::ADVERTISEMENT_NOT_FOUND => 'NOT_FOUND',
        };
    }
}
