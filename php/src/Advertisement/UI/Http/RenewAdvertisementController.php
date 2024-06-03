<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\UI\Http;

use Demo\App\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementCommand;
use Demo\App\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementUseCase;
use Demo\App\Common\UI\CommonController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;

final class RenewAdvertisementController extends CommonController
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

            return $this->processSuccessfulCommand();
        } catch (\UnexpectedValueException $exception) {
            return $this->processNotFoundException($exception);
        } catch (\Throwable $exception) {
            return $this->processGenericException($exception);
        }
    }
}
