<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\UI\Http;

use Demo\App\Advertisement\Application\Command\UpdateAdvertisement\UpdateAdvertisementCommand;
use Demo\App\Advertisement\Application\Command\UpdateAdvertisement\UpdateAdvertisementUseCase;
use Demo\App\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Common\UI\CommonController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Exception;

final class UpdateAdvertisementController extends CommonController
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

            $result = $this->useCase->execute($command);

            if ($result->isRight()) {
                return $this->processSuccessfulCommand();
            }
            if ($result->getLeft() instanceof AdvertisementNotFoundException){
                return $this->processNotFoundCommand($result);
            }

            return $this->processFailedCommand($result);
        } catch (Exception $exception) {
            return $this->processGenericException($exception);
        }
    }
}
