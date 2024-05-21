<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Infrastructure\Errors;

use Demo\App\Common\Result;

final readonly class ZeroRecordsError
{
    public static function build(string $id): Result
    {
        return Result::failure('Advertisement not found with ID: ' . $id, 'NOT_FOUND');
    }
}
