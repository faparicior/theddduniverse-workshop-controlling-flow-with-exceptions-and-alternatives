<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\UI\Http;

use Demo\App\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementCommand;
use Demo\App\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementUseCase;
use Demo\App\Common\Application\ApplicationException;
use Demo\App\Common\Domain\DomainException;
use Demo\App\Common\UserInterface\CommonController;
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

            $this->useCase->execute($command);

            return $this->processSuccessfulCommand();
        } catch (DomainException|ApplicationException $exception) {
            return $this->processDomainOrApplicationExceptionResponse($exception);
        } catch (Exception $exception) {
            return $this->processGenericException($exception);
        }
    }
}
