<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Errors;

use Demo\App\Common\Result;

final readonly class PasswordDoesNotMatchError
{
    public static function build(): Result
    {
        return Result::failure('Invalid password');
    }
}
