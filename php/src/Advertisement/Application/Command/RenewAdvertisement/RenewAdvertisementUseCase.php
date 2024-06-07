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
                onSuccess: fn($id) => $this->processAdvertisement($id, $command),
                onFailure: fn($error) => Result::failure($error)
            );
    }

    private function processAdvertisement($id, RenewAdvertisementCommand $command): Result
    {
        return $this->advertisementRepository->findById($id)
            ->map(fn($advertisement) => $advertisement)
            ->fold(
                onSuccess: fn($advertisement) => $this->processPasswordMatch($advertisement, $command),
                onFailure: function($error) use ($id) {
                    if ($error instanceof ZeroRecordsException) {
                        return Result::failure(AdvertisementNotFoundException::withId($id->value()));
                    }
                    return Result::failure($error);
                }
            );
    }

    private function processPasswordMatch(Advertisement $advertisement, RenewAdvertisementCommand $command): Result
    {
        return $this->validatePasswordMatch($command->password, $advertisement)
            ->map(fn($isValid) => $isValid)
            ->fold(
                onSuccess: fn($isValid) => $this->processNewPassword($advertisement, $command),
                onFailure: fn($error) => Result::failure($error)
            );
    }

    private function processNewPassword(Advertisement $advertisement, RenewAdvertisementCommand $command): Result
    {
        return Password::fromPlainPassword($command->password)
            ->map(fn($newPassword) => $newPassword)
            ->fold(
                onSuccess: fn($newPassword) => $this->processRenewAdvertisement($advertisement, $newPassword),
                onFailure: fn($error) => Result::failure($error)
            );
    }

    private function processRenewAdvertisement(Advertisement $advertisement, Password $newPassword): Result
    {
        return $advertisement->renew($newPassword)
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

    private function validatePasswordMatch(string $password, Advertisement $advertisement): Result
    {
        if (!$advertisement->password()->isValidatedWith($password)) {
            return Result::failure(InvalidPasswordException::build());
        }
        return Result::success();
    }
}
