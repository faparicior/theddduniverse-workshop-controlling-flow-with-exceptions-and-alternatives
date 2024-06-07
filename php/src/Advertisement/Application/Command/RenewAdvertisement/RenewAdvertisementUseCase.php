<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\RenewAdvertisement;

use Demo\App\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Demo\App\Advertisement\Infrastructure\Exceptions\ZeroRecordsException;
use Demo\App\Common\Result;
use Exception;

final class RenewAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(RenewAdvertisementCommand $command): Result
    {
        return AdvertisementId::build($command->id)
            ->map(fn($id) => $id)
            ->fold(
                onSuccess: fn($id) => $this->findAdvertisement($id, $command),
                onFailure: fn($error) => Result::failure($error)
            );
    }

    private function findAdvertisement($id, RenewAdvertisementCommand $command): Result
    {
        return $this->advertisementRepository->findById($id)
            ->map(fn($advertisement) => $advertisement)
            ->fold(
                onSuccess: fn($advertisement) => $this->generateNewPassword($advertisement, $command),
                onFailure: function($error) use ($id) {
                    if ($error instanceof ZeroRecordsException) {
                        return Result::failure(AdvertisementNotFoundException::withId($id->value()));
                    }
                    return Result::failure($error);
                }
            );
    }

    private function generateNewPassword(Advertisement $advertisement, RenewAdvertisementCommand $command): Result
    {
        return Password::fromPlainPassword($command->password)
            ->map(fn($newPassword) => $newPassword)
            ->fold(
                onSuccess: fn($newPassword) => $this->renewAdvertisement($advertisement, $newPassword, $command),
                onFailure: fn($error) => Result::failure($error)
            );
    }

    private function renewAdvertisement(Advertisement $advertisement, Password $password, RenewAdvertisementCommand $command): Result
    {
        if (!$advertisement->password()->isValidatedWith($command->password)) {
            return Result::failure(InvalidPasswordException::build());
        }

        return $advertisement->renew($password)
            ->map(fn($renewedAdvertisement) => $renewedAdvertisement)
            ->fold(
                onSuccess: fn($renewedAdvertisement) => $this->saveAdvertisement($renewedAdvertisement),
                onFailure: fn($error) => Result::failure($error)
            );
    }

    private function saveAdvertisement(Advertisement $advertisement): Result
    {
        $this->advertisementRepository->save($advertisement);
        return Result::success();
    }
}
