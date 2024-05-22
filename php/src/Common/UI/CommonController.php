<?php
declare(strict_types=1);

namespace Demo\App\Common\UI;

use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Exception;

abstract class CommonController
{
    public abstract function request(FrameworkRequest $request): FrameworkResponse;

    protected function processGenericException(Exception $exception): FrameworkResponse
    {
        return new FrameworkResponse(
            FrameworkResponse::STATUS_BAD_REQUEST,
            [
                'errors' => $exception->getMessage(),
                'code' => FrameworkResponse::STATUS_BAD_REQUEST,
                'message' => $exception->getMessage(),
            ]
        );
    }

    protected function processSuccessfulCommand(): FrameworkResponse
    {
        return new FrameworkResponse(
            FrameworkResponse::STATUS_OK,
            [
                'errors' => '',
                'code' => FrameworkResponse::STATUS_OK,
                'message' => '',
            ]
        );
    }

    protected function processSuccessfulCreateCommand(): FrameworkResponse
    {
        return new FrameworkResponse(
            FrameworkResponse::STATUS_CREATED,
            [
                'errors' => '',
                'code' => FrameworkResponse::STATUS_CREATED,
                'message' => '',
            ]
        );
    }

    protected function processNotFoundException(Exception $exception): FrameworkResponse
    {
        return new FrameworkResponse(
            FrameworkResponse::STATUS_NOT_FOUND,
            [
                'errors' => $exception->getMessage(),
                'code' => FrameworkResponse::STATUS_NOT_FOUND,
                'message' => $exception->getMessage(),
            ]
        );
    }
}