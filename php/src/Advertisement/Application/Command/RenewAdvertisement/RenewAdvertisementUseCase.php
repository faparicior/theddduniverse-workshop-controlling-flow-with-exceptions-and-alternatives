<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\RenewAdvertisement;

use Demo\App\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Chemem\Bingo\Functional\Functors\Monads\Either;

final class RenewAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    public function execute(RenewAdvertisementCommand $command): Either
    {
        return AdvertisementId::build($command->id)
            ->flatMap(function($advertisementId) use ($command) {
                return $this->advertisementRepository->findById($advertisementId)
                    ->flatMap(function($advertisement) use ($command) {
                        $passwordValidation = $this->validatePasswordMatch($command->password, $advertisement);
                        if ($passwordValidation->isLeft()) {
                            return $passwordValidation;
                        }
                        return Password::fromPlainPassword($command->password)
                            ->flatMap(function($newPassword) use ($advertisement) {
                                return $advertisement->renew($newPassword);
                            })
                            ->map(function($advertisement) {
                                $this->advertisementRepository->save($advertisement);
                                return Either::right(null);
                            });
                    });
            });
    }

    private function validatePasswordMatch(string $password, Advertisement $advertisement): Either
    {
        return $advertisement->password()->isValidatedWith($password)
            ? Either::right($advertisement)
            : Either::left(InvalidPasswordException::build());
    }
}