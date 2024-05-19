<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\UI\Http;

use Demo\App\Advertisement\Application\Command\PublishAdvertisement\PublishAdvertisementCommand;
use Demo\App\Advertisement\Application\Command\PublishAdvertisement\PublishAdvertisementUseCase;
use Demo\App\Common\Application\ApplicationException;
use Demo\App\Common\Domain\DomainException;
use Demo\App\Common\UserInterface\GenericController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Exception;

final class PublishAdvertisementController extends GenericController
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

            return $this->processSuccessfulCreateCommand();
        } catch (DomainException|ApplicationException $exception) {
            return $this->processDomainOrApplicationExceptionResponse($exception);
        } catch (Exception $exception) {
            return $this->processGenericException($exception);
        }
    }
}
