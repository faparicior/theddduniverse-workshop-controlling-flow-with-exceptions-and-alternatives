<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Errors;

enum PasswordErrors
{
    case PROBLEM_HASHING_PASSWORD;

    public function getMessage(): string
    {
        return match ($this) {
            self::PROBLEM_HASHING_PASSWORD => 'Problem hashing password',
        };
    }
}
