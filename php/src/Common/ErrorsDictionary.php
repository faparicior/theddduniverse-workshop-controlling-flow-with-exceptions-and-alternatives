<?php
declare(strict_types=1);

namespace Demo\App\Common;

interface ErrorsDictionary
{

    public function getMessage(): string;

    public function getCode(): string;
}