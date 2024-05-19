<?php
declare(strict_types=1);

namespace Demo\App\Common\UserInterface;

use Demo\App\Common\Application\ApplicationException;
use Demo\App\Common\Domain\DomainException;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Exception;

abstract class GenericController
{
    public abstract function request(FrameworkRequest $request): FrameworkResponse;

    protected function processDomainOrApplicationExceptionResponse(ApplicationException|DomainException|Exception $e): FrameworkResponse
    {
        $responseCode = $e->getCode() == 404 ? FrameworkResponse::STATUS_NOT_FOUND : FrameworkResponse::STATUS_BAD_REQUEST;
        return new FrameworkResponse(
            $responseCode,
            [
                'errors' => $e->getMessage(),
                'code' => $responseCode,
                'message' => $e->getMessage(),
            ]
        );
    }

    protected function processGenericException(Exception $exception): FrameworkResponse
    {
        return new FrameworkResponse(
            FrameworkResponse::STATUS_INTERNAL_SERVER_ERROR,
            [
                'errors' => $exception->getMessage(),
                'code' => FrameworkResponse::STATUS_INTERNAL_SERVER_ERROR,
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
}