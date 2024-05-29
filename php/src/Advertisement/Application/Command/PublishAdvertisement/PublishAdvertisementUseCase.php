<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\PublishAdvertisement;

use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Exceptions\AdvertisementAlreadyExistsException;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Demo\App\Common\Result;

final class PublishAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws \Throwable
     */
    public function execute(PublishAdvertisementCommand $command): Result
    {
        $advertisementResult = $this->validateAdvertisement($command);
        if ($advertisementResult->isFailure()) {
            return $advertisementResult;
        }
        /** @var Advertisement $advertisement */
        $advertisement = $advertisementResult->getOrThrow();

        $findAdvertisementResult = $this->advertisementRepository->findById($advertisement->id());
        if ($findAdvertisementResult->isSuccess()) {
            return Result::failure(AdvertisementAlreadyExistsException::withId($advertisement->id()->value()));
        }

        $this->advertisementRepository->save($advertisement);

        return Result::success();
    }

    private function validateAdvertisement(PublishAdvertisementCommand $command): Result
    {
        $passwordResult = Password::fromPlainPassword($command->password);
        if ($passwordResult->isFailure()) {
            return $passwordResult;
        }
        /** @var Password $password */
        $password = $passwordResult->getOrThrow();

        return Advertisement::build(
            $command->id,
            $command->description,
            $command->email,
            $password,
            new \DateTime(),
        );
    }
}
