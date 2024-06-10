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
                $findAdvertisementResult = $this->advertisementRepository->findById($advertisement->id());
                if ($findAdvertisementResult->isRight()) {
                    return Either::left(AdvertisementAlreadyExistsException::withId($advertisement->id()->value()));
                }
                return Either::right($advertisement);
            })
            ->map(function($advertisement) {
                return $this->advertisementRepository->save($advertisement);
            });
    }
}