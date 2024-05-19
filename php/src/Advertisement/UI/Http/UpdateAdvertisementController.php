<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\UI\Http;

use Demo\App\Advertisement\Application\Command\UpdateAdvertisement\UpdateAdvertisementCommand;
use Demo\App\Advertisement\Application\Command\UpdateAdvertisement\UpdateAdvertisementUseCase;
use Demo\App\Common\Application\ApplicationException;
use Demo\App\Common\Domain\DomainException;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Exception;

final class UpdateAdvertisementController
{
    public function __construct(private UpdateAdvertisementUseCase $useCase)
    {
    }

    public function request(FrameworkRequest $request): FrameworkResponse
    {
        try {
            $command = new UpdateAdvertisementCommand(
                $request->getIdPath(),
                ($request->content())['description'],
                ($request->content())['email'],
                ($request->content())['password'],
            );

            $this->useCase->execute($command);

            return new FrameworkResponse(
                FrameworkResponse::STATUS_OK,
                [
                    'errors' => '',
                    'code' => FrameworkResponse::STATUS_OK,
                    'message' => '',
                ]
            );
        } catch (DomainException|ApplicationException $e) {
            return new FrameworkResponse(
                FrameworkResponse::STATUS_BAD_REQUEST,
                [
                    'errors' => $e->getMessage(),
                    'code' => FrameworkResponse::STATUS_BAD_REQUEST,
                    'message' => $e->getMessage(),
                ]
            );
        } catch (Exception $e) {
            return new FrameworkResponse(
                FrameworkResponse::STATUS_INTERNAL_SERVER_ERROR,
                [
                    'errors' => $e->getMessage(),
                    'code' => FrameworkResponse::STATUS_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
}
