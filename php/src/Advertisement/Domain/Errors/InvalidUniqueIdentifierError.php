<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Errors;

use Demo\App\Common\Result;

final readonly class InvalidUniqueIdentifierError
{
    public static function build(string $id): Result
    {
        return Result::failure(sprintf('Invalid unique identifier format for %s', $id));
    }
}
