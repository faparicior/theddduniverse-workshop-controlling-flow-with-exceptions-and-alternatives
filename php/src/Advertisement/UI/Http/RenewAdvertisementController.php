<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\UI\Http;

use Demo\App\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementCommand;
use Demo\App\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementUseCase;
use Demo\App\Common\Domain\DomainException;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Exception;

final class RenewAdvertisementController
{
    public function __construct(private RenewAdvertisementUseCase $useCase)
    {
    }

    public function request(FrameworkRequest $request): FrameworkResponse
    {
        try {
            $command = new RenewAdvertisementCommand(
                $request->getIdPath(),
                ($request->content())['password'],
            );

            $this->useCase->execute($command);

            return new FrameworkResponse(
                FrameworkResponse::STATUS_OK,
                []
            );
        } catch (DomainException $e) {
            return new FrameworkResponse(
                FrameworkResponse::STATUS_BAD_REQUEST,
                []
            );
        } catch (Exception $e) {
            return new FrameworkResponse(
                FrameworkResponse::STATUS_INTERNAL_SERVER_ERROR,
                []
            );
        }
    }
}
