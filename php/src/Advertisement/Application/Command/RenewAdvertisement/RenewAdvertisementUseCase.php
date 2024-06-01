<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\RenewAdvertisement;

use Demo\App\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Chemem\Bingo\Functional\Functors\Monads\Either;
use Demo\App\Advertisement\Infrastructure\Exceptions\ZeroRecordsException;

final class RenewAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    public function execute(RenewAdvertisementCommand $command): Either
    {
        return AdvertisementId::build($command->id)
            ->flatMap(fn($advertisementId) => $this->findAdvertisement($advertisementId))
            ->flatMap(fn($advertisement) => $this->validatePassword($command->password, $advertisement))
            ->flatMap(fn($advertisement) => $this->renewAdvertisement($command->password, $advertisement))
            ->map(fn($advertisement) => $this->saveAdvertisement($advertisement));
    }

    private function findAdvertisement($advertisementId): Either
    {
        $advertisementResult = $this->advertisementRepository->findById($advertisementId);
        if ($advertisementResult->isLeft() && $advertisementResult->getLeft() instanceof ZeroRecordsException) {
            return Either::left(AdvertisementNotFoundException::withId($advertisementId->value()));
        }

        return $advertisementResult;
    }

    private function validatePassword(string $password, Advertisement $advertisement): Either
    {
        return $advertisement->password()->isValidatedWith($password)
            ? Either::right($advertisement)
            : Either::left(InvalidPasswordException::build());
    }

    private function renewAdvertisement(string $password, Advertisement $advertisement): Either
    {
        return Password::fromPlainPassword($password)
            ->flatMap(fn($newPassword) => $advertisement->renew($newPassword));
    }

    private function saveAdvertisement(Advertisement $advertisement): Either
    {
        $this->advertisementRepository->save($advertisement);
        return Either::right(null);
    }
}