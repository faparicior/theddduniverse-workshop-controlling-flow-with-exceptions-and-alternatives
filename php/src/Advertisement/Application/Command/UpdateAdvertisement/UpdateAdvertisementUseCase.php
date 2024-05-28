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
        return Result::runCatching($command, function ($command) {
            /** @var AdvertisementId $advertisementId */
            $advertisementId = AdvertisementId::build($command->id)->getOrThrow();

            /** @var Advertisement $advertisement */
            $advertisement = $this->getAdvertisement($advertisementId)->getOrThrow();

            $this->validatePasswordMatch($command->password, $advertisement)->getOrThrow();

            /** @var Password $newPassword */
            $newPassword = Password::fromPlainPassword($command->password)->getOrThrow();
            $advertisement->update(
                $command->description,
                $command->email,
                $newPassword,
            );

            $this->advertisementRepository->save($advertisement);

            return Result::success();
        });
    }

    public function getAdvertisement(AdvertisementId $advertisementId): Result
    {
        try {
            $advertisement = $this->advertisementRepository->findById($advertisementId)->getOrThrow();
            return Result::success($advertisement);
        } catch (\Throwable $e) {
            if ($e instanceof ZeroRecordsException) {
                return Result::failure(AdvertisementNotFoundException::withId($advertisementId->value()));
            }
            return Result::failure($e);
        }
    }

    private function validatePasswordMatch(string $password, Advertisement $advertisement): Result
    {
        if (!$advertisement->password()->isValidatedWith($password)) {
            return Result::failure(InvalidPasswordException::build());
        }
        return Result::success();
    }
}
