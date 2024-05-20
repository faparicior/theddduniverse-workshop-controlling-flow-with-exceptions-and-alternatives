<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\PublishAdvertisement;

use Demo\App\Advertisement\Application\Errors\ErrorDictionary;
use Demo\App\Advertisement\Application\Exceptions\AdvertisementAlreadyExistsException;
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
        if (!$result->isSuccess()) {
            return $result;
        }

        $result = AdvertisementId::build($command->id);
        if (!$result->isSuccess()) {
            return $result;
        }

        /** @var AdvertisementId $advertisementId */
        $advertisementId = $result->getData();
        if ($this->advertisementRepository->findById($advertisementId)) {
            return Result::failure(ErrorDictionary::ADVERTISEMENT_WITH_ID_S_ALREADY_EXISTS_MESSAGE->value);
        }

        $advertisement = new Advertisement(
            $advertisementId,
            new Description($command->description),
            new Email($command->email),
            Password::fromPlainPassword($command->password),
            new AdvertisementDate(new \DateTime()),
        );

//        $this->advertisementRepository->save($advertisement);
    }
}
