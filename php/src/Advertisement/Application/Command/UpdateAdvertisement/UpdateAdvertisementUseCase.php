<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\UpdateAdvertisement;

use Demo\App\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Demo\App\Advertisement\Infrastructure\Exceptions\ZeroRecordsException;
use Demo\App\Common\Result;
use Exception;

final class UpdateAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(UpdateAdvertisementCommand $command): Result
    {
        $advertisementIdResult = AdvertisementId::build($command->id);
        if ($advertisementIdResult->isFailure()) {
            return $advertisementIdResult;
        }
        /** @var AdvertisementId $advertisementId */
        $advertisementId = $advertisementIdResult->getOrThrow();

        $advertisementResult = $this->advertisementRepository->findById($advertisementId);

        if ($advertisementResult->isFailure()) {
            if ($advertisementResult->exception() instanceof ZeroRecordsException) {
                return Result::failure(AdvertisementNotFoundException::withId($command->id));
            }
        }
        /** @var Advertisement $advertisement */
        $advertisement = $advertisementResult->getOrThrow();

        $passwordMatchResult = $this->validatePasswordMatch($command->password, $advertisement);
        if ($passwordMatchResult->isFailure()) {
            return $passwordMatchResult;
        }

        $newPasswordResult = Password::fromPlainPassword($command->password);
        if ($newPasswordResult->isFailure()) {
            return $newPasswordResult;
        }
        /** @var Password $newPassword */
        $newPassword = $newPasswordResult->getOrThrow();

        $advertisement->update(
            $command->description,
            $command->email,
            $newPassword,
        );

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
