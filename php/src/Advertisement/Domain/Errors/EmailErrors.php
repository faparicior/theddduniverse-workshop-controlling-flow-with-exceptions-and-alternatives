<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Errors;

enum EmailErrors
{
    case INVALID_EMAIL_FORMAT;

    public function getMessage(): string
    {
        return match ($this) {
            self::INVALID_EMAIL_FORMAT => 'Invalid email format',
        };
    }
}
