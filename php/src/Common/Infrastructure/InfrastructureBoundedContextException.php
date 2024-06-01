<?php
declare(strict_types=1);

namespace Demo\App\Common\Infrastructure;

use Demo\App\Common\Exceptions\BoundedContextException;

abstract class InfrastructureBoundedContextException extends BoundedContextException
{
}
