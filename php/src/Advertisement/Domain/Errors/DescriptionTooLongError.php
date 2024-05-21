<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Errors;

use Demo\App\Common\Result;

final readonly class DescriptionTooLongError
{
    public static function build(string $description): Result
    {
        return Result::failure(sprintf('Description has more than 200 characters: Has %d characters', mb_strlen($description)));
    }
}
