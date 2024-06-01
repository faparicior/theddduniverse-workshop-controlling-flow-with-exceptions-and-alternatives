<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\PublishAdvertisement;

use Chemem\Bingo\Functional\Functors\Monads\Either;
use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Exceptions\AdvertisementAlreadyExistsException;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;

final class PublishAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws \Throwable
     */
    public function execute(PublishAdvertisementCommand $command): Either
    {
        $advertisementResult = $this->validateAdvertisement($command);
        if ($advertisementResult->isLeft()) {
            return $advertisementResult;
        }
        /** @var Advertisement $advertisement */
        $advertisement = $advertisementResult->getRight();

        $findAdvertisementResult = $this->advertisementRepository->findById($advertisement->id());
        if ($findAdvertisementResult->isRight()) {
            return Either::left(AdvertisementAlreadyExistsException::withId($advertisement->id()->value()));
        }

        $this->advertisementRepository->save($advertisement);

        return Either::right(null);
    }

    private function validateAdvertisement(PublishAdvertisementCommand $command): Either
    {
        $passwordResult = Password::fromPlainPassword($command->password);
        if ($passwordResult->isLeft()) {
            return $passwordResult;
        }
        /** @var Password $password */
        $password = $passwordResult->getRight();

        return Advertisement::build(
            $command->id,
            $command->description,
            $command->email,
            $password,
            new \DateTime(),
        );
    }
}
