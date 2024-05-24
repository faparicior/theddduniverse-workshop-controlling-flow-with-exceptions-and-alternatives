<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\UI\Http;

use Demo\App\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementCommand;
use Demo\App\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementUseCase;
use Demo\App\Advertisement\Application\Exceptions\AdvertisementNotFoundException;
use Demo\App\Common\UI\CommonController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Exception;

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

            $result = $this->useCase->execute($command);
            if ($result->isSuccess()) {
                return $this->processSuccessfulCommand();
            }
            if ($result->exception() instanceof AdvertisementNotFoundException){
                return $this->processNotFoundCommand($result);
            }

            return $this->processFailedCommand($result);
        } catch (Exception $exception) {
            return $this->processGenericException($exception);
        }
    }
}
