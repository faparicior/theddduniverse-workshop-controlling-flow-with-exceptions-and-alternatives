<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\PublishAdvertisement;

use Demo\App\Advertisement\Application\Errors\PublishAdvertisementErrors;
use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementDate;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Description;
use Demo\App\Advertisement\Domain\Model\ValueObject\Email;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Demo\App\Common\Result;

final class PublishAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    public function execute(PublishAdvertisementCommand $command): Result
    {
        $advertisementResult = $this->validateAdvertisement($command);
        if ($advertisementResult->isError()) {
            return $advertisementResult;
        }

        /** @var Advertisement $advertisement */
        $advertisement = $advertisementResult->unwrap();

        $findAdvertisementResult = $this->advertisementRepository->findById($advertisement->id());
        if ($findAdvertisementResult->isSuccess()) {
            return Result::failure(
                sprintf(
                    PublishAdvertisementErrors::ADVERTISEMENT_ALREADY_EXISTS->getMessage(),
                    $advertisement->id()->value()
                )
            );
        }

        $saveResult = $this->advertisementRepository->save($advertisement);

        if ($saveResult->isError()) {
            return $saveResult;
        }

        return Result::success();
    }

    private function validateAdvertisement(PublishAdvertisementCommand $command): Result
    {
        $advertisementIdResult = AdvertisementId::build($command->id);
        if ($advertisementIdResult->isError()) {
            return $advertisementIdResult;
        }
        /** @var AdvertisementId $advertisementId */
        $advertisementId = $advertisementIdResult->unwrap();

        $descriptionResult = Description::build($command->description);
        if ($descriptionResult->isError()) {
            return $descriptionResult;
        }
        /** @var Description $description */
        $description = $descriptionResult->unwrap();

        $emailResult = Email::build($command->email);
        if ($emailResult->isError()) {
            return $emailResult;
        }
        /** @var Email $email */
        $email = $emailResult->unwrap();

        $passwordResult = Password::fromPlainPassword($command->password);
        if ($passwordResult->isError()) {
            return $passwordResult;
        }
        /** @var Password $password */
        $password = $passwordResult->unwrap();

        $advertisementDateResult = AdvertisementDate::build(new \DateTime());
        if ($advertisementDateResult->isError()) {
            return $advertisementDateResult;
        }

        /** @var AdvertisementDate $date */
        $date = $advertisementDateResult->unwrap();

        return Advertisement::build(
            $advertisementId,
            $description,
            $email,
            $password,
            $date,
        );
    }
}
