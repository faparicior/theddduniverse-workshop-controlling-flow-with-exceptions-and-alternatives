<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Errors;

enum ErrorDictionary: string
{
    case ADVERTISEMENT_WITH_ID_S_ALREADY_EXISTS_MESSAGE = 'Advertisement with id %s already exists';
}
