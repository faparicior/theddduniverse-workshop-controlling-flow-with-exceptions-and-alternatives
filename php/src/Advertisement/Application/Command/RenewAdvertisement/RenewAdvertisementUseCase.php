<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\RenewAdvertisement;

use Demo\App\Advertisement\Application\Errors\RenewAdvertisementErrors;
use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
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
        $advertisementIdResult = $this->validateAdvertisementId($command);
        if ($advertisementIdResult->isError()) {
            return $advertisementIdResult;
        }
        /** @var AdvertisementId $advertisementId */
        $advertisementId = $advertisementIdResult->unwrap();

        $advertisementResult = $this->advertisementRepository->findById($advertisementId);
        if ($advertisementResult->isError()) {
            return $advertisementResult;
        }
        /** @var Advertisement $advertisement */
        $advertisement = $advertisementResult->unwrap();

        $passwordMatchResult = $this->validatePasswordMatch($command->password, $advertisement);
        if ($passwordMatchResult->isError()) {
            return $passwordMatchResult;
        }

        $newPasswordResult = Password::fromPlainPassword($command->password);
        if ($newPasswordResult->isError()) {
            return $newPasswordResult;
        }
        /** @var Password $newPassword */
        $newPassword = $newPasswordResult->unwrap();

        $renewResult = $advertisement->renew($newPassword);
        if ($renewResult->isError()) {
            return $renewResult;
        }

        /** @var Advertisement $advertisement */
        $advertisement = $renewResult->unwrap();

        $this->advertisementRepository->save($advertisement);
        return Result::success();
    }

    private function validateAdvertisementId(RenewAdvertisementCommand $command): Result
    {
        return AdvertisementId::build($command->id);
    }

    private function validatePasswordMatch(string $password, Advertisement $advertisement): Result
    {
        if (!$advertisement->password()->isValidatedWith($password)) {
            return Result::failure(RenewAdvertisementErrors::PASSWORD_DOES_NOT_MATCH->getMessage());
        }
        return Result::success();
    }
}
