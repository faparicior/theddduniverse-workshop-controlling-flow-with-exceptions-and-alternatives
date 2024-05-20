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
use Exception;

final class PublishAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(PublishAdvertisementCommand $command): Result
    {
        $result = AdvertisementId::build($command->id);
        if ($result->isError()) {
            return $result;
        }
        /** @var AdvertisementId $advertisementId */
        $advertisementId = $result->unwrap();

        $result = Description::build($command->description);
        if ($result->isError()) {
            return $result;
        }
        /** @var Description $description */
        $description = $result->unwrap();

        $result = Email::build($command->email);
        if ($result->isError()) {
            return $result;
        }
        /** @var Email $email */
        $email = $result->unwrap();

        $result = Password::fromPlainPassword($command->password);
        if ($result->isError()) {
            return $result;
        }
        /** @var Password $password */
        $password = $result->unwrap();

        $result = $this->advertisementRepository->findById($advertisementId);

        if($result->isSuccess()) {
            return Result::failure(sprintf(PublishAdvertisementErrors::ADVERTISEMENT_ALREADY_EXISTS->getMessage(), $advertisementId->value()));
        }

        $result = AdvertisementDate::build(new \DateTime());
        if ($result->isError()) {
            return $result;
        }

        /** @var AdvertisementDate $date */
        $date = $result->unwrap();

        $result = Advertisement::build(
            $advertisementId,
            $description,
            $email,
            $password,
            $date,
        );

        if ($result->isError()) {
            return $result;
        }

        /** @var Advertisement $advertisement */
        $advertisement = $result->unwrap();

        $result = $this->advertisementRepository->save($advertisement);

        if ($result->isError()) {
            return $result;
        }

        return Result::success();
    }
}
