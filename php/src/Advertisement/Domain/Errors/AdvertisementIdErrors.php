<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Errors;

enum AdvertisementIdErrors
{
    case ADVERTISEMENT_ID_INVALID;

    public function getMessage(): string
    {
        return match ($this) {
            self::ADVERTISEMENT_ID_INVALID => 'Invalid unique identifier format for %s',
        };
    }

}
