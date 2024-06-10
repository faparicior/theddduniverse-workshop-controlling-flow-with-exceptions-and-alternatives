<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\UpdateAdvertisement;

use Demo\App\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Chemem\Bingo\Functional\Functors\Monads\Either;
use Demo\App\Advertisement\Infrastructure\Exceptions\ZeroRecordsException;

final class UpdateAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    public function execute(UpdateAdvertisementCommand $command): Either
    {
        return AdvertisementId::build($command->id)
            ->flatMap(function($advertisementId) use ($command) {
                $advertisementResult = $this->advertisementRepository->findById($advertisementId);
                if ($advertisementResult->isLeft() && $advertisementResult->getLeft() instanceof ZeroRecordsException) {
                    return Either::left(AdvertisementNotFoundException::withId($advertisementId->value()));
                }
                return $advertisementResult
                    ->flatMap(function($advertisement) use ($command) {
                        if (!$advertisement->password()->isValidatedWith($command->password)){
                            return Either::left(InvalidPasswordException::build());
                        }
                        return Password::fromPlainPassword($command->password)
                            ->map(function($newPassword) use ($advertisement, $command) {
                                $advertisement->update(
                                    $command->description,
                                    $command->email,
                                    $newPassword,
                                );
                                $this->advertisementRepository->save($advertisement);
                                return Either::right(null);
                            });
                    });
            });
    }

    public function execute2(UpdateAdvertisementCommand $command): Either
    {
        $advertisementIdResult = AdvertisementId::build($command->id);

        if ($advertisementIdResult->isLeft()) {
            return $advertisementIdResult;
        }

        $advertisementId = $advertisementIdResult->getRight();
        $advertisementResult = $this->advertisementRepository->findById($advertisementId);

        if ($advertisementResult->isLeft() && $advertisementResult->getLeft() instanceof ZeroRecordsException) {
            return Either::left(AdvertisementNotFoundException::withId($advertisementId->value()));
        }

        $advertisement = $advertisementResult->getRight();

        if (!$advertisement->password()->isValidatedWith($command->password)){
            return Either::left(InvalidPasswordException::build());
        }

        $newPasswordResult = Password::fromPlainPassword($command->password);

        if ($newPasswordResult->isLeft()) {
            return $newPasswordResult;
        }

        $newPassword = $newPasswordResult->getRight();
        $advertisement->update($command->description, $command->email, $newPassword);
        $this->advertisementRepository->save($advertisement);

        return Either::right(null);
    }
}