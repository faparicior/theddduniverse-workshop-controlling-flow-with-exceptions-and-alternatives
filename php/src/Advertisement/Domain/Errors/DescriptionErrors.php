<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Errors;

enum DescriptionErrors
{
    case DESCRIPTION_MIN_LENGTH_INVALID;
    case DESCRIPTION_MAX_LENGTH_INVALID;

    public function getMessage(): string
    {
        return match ($this) {
            self::DESCRIPTION_MIN_LENGTH_INVALID => 'Description empty',
            self::DESCRIPTION_MAX_LENGTH_INVALID => 'Description has more than 200 characters: Has %s characters',
        };
    }
}
