<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Errors;

use Demo\App\Common\ErrorsDictionary;

enum PublishAdvertisementErrors implements ErrorsDictionary
{
    case ADVERTISEMENT_ALREADY_EXISTS;

    public function getMessage(): string
    {
        return match ($this) {
            self::ADVERTISEMENT_ALREADY_EXISTS => 'Advertisement with id %s already exists',
        };
    }

    public function getCode(): string
    {
        return '';
    }
}
