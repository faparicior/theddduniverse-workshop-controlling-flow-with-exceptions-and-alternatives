<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Errors;

use Demo\App\Common\Result;

final readonly class AdvertisementAlreadyExistsError
{
    public static function build(string $id): Result
    {
        return Result::failure(sprintf('Advertisement with id %s already exists', $id));
    }
}
