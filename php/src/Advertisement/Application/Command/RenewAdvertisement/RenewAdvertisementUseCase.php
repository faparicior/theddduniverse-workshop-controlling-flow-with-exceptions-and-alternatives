<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\RenewAdvertisement;

use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Exception;
use UnexpectedValueException;

final class RenewAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(RenewAdvertisementCommand $command): void
    {
        $advertisementId = new AdvertisementId($command->id);
        $advertisement = $this->advertisementRepository->findById($advertisementId);

        if (!$advertisement) {
            throw new UnexpectedValueException('Advertisement not found with Id: ' . $advertisementId->value());
        }


        if (!$advertisement->password()->isValidatedWith($command->password)) {
            throw new Exception('Invalid password');
        }

        $advertisement->renew(Password::fromPlainPassword($command->password));

        $this->advertisementRepository->save($advertisement);
    }
}
