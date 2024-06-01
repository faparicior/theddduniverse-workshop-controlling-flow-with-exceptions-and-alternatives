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

    public function execute(PublishAdvertisementCommand $command): Either
    {
        return Password::fromPlainPassword($command->password)
            ->flatMap(function($password) use ($command) {
                return Advertisement::build(
                    $command->id,
                    $command->description,
                    $command->email,
                    $password,
                    new \DateTime(),
                );
            })
            ->flatMap(function($advertisement) {
                return $this->ensureThatAdvertisementDoesNotExist($advertisement);
            })
            ->map(function($advertisement) {
                return $this->advertisementRepository->save($advertisement);
            });
    }

    private function ensureThatAdvertisementDoesNotExist(Advertisement $advertisement): Either
    {
        $findAdvertisementResult = $this->advertisementRepository->findById($advertisement->id());
        return $findAdvertisementResult->isRight()
            ? Either::left(AdvertisementAlreadyExistsException::withId($advertisement->id()->value()))
            : Either::right($advertisement);
    }
}