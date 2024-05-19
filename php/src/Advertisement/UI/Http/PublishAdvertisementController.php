<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\UI\Http;

use Demo\App\Advertisement\Application\Command\PublishAdvertisement\PublishAdvertisementCommand;
use Demo\App\Advertisement\Application\Command\PublishAdvertisement\PublishAdvertisementUseCase;
use Demo\App\Common\Application\ApplicationException;
use Demo\App\Common\Domain\DomainException;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Exception;

final class PublishAdvertisementController
{
    public function __construct(private PublishAdvertisementUseCase $useCase)
    {
    }

    public function request(FrameworkRequest $request): FrameworkResponse
    {
        try {
            $command = new PublishAdvertisementCommand(
                ($request->content())['id'],
                ($request->content())['description'],
                ($request->content())['email'],
                ($request->content())['password'],
            );

            $this->useCase->execute($command);

            return new FrameworkResponse(
                FrameworkResponse::STATUS_CREATED,
                [
                    'errors' => '',
                    'code' => FrameworkResponse::STATUS_CREATED,
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
