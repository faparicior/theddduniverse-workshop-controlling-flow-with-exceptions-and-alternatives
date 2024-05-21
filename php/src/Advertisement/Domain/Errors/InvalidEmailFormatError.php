<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Errors;

use Demo\App\Common\Result;

final readonly class InvalidEmailFormatError
{
    public static function build(string $email): Result
    {
        return Result::failure('Invalid email format ' . $email);
    }
}
