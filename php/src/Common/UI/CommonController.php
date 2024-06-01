<?php
declare(strict_types=1);

namespace Demo\App\Common\UI;

use Demo\App\Common\Application\ApplicationException;
use Demo\App\Common\Infrastructure\InfrastructureException;
use Demo\App\Common\Result;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Exception;
use function PHPUnit\Framework\isInstanceOf;

abstract class CommonController
{
    public abstract function request(FrameworkRequest $request): FrameworkResponse;

    protected function processDomainOrApplicationExceptionResponse(ApplicationException|InfrastructureException|Exception $e): FrameworkResponse
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

    protected function processFailedCommand(Result $result): FrameworkResponse
    {
        return new FrameworkResponse(
            FrameworkResponse::STATUS_BAD_REQUEST,
            [
                'errors' => $result->exception()->getMessage(),
                'code' => FrameworkResponse::STATUS_BAD_REQUEST,
                'message' => $result->exception()->getMessage(),
            ]
        );
    }

    protected function processNotFoundCommand(Result $result): FrameworkResponse
    {
        return new FrameworkResponse(
            FrameworkResponse::STATUS_NOT_FOUND,
            [
                'errors' => $result->exception()->getMessage(),
                'code' => FrameworkResponse::STATUS_NOT_FOUND,
                'message' => $result->exception()->getMessage(),
            ]
        );
    }

    protected function processGenericException(?Exception $exception): FrameworkResponse
    {
        $errors = '';
        $message = '';
        if ($exception !== null) {
            $errors = $exception->getMessage();
            $message = $exception->getMessage();
        }

        return new FrameworkResponse(
            FrameworkResponse::STATUS_INTERNAL_SERVER_ERROR,
            [
                'errors' => $errors,
                'code' => FrameworkResponse::STATUS_INTERNAL_SERVER_ERROR,
                'message' => $message,
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