<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Errors;

use Demo\App\Common\Result;

final readonly class ProblemHashingPasswordError
{
    public static function build(): Result
    {
        return Result::failure('Problem hashing password');
    }
}
