<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Errors;

enum RenewAdvertisementErrors
{
    case PASSWORD_DOES_NOT_MATCH;

    public function getMessage(): string
    {
        return match ($this) {
            self::PASSWORD_DOES_NOT_MATCH => 'Invalid password',
        };
    }
}
