<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\UpdateAdvertisement;

use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Model\ValueObject\Email;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Exception;

final class UpdateAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(UpdateAdvertisementCommand $command): void
    {
        $advertisement = $this->advertisementRepository->findById($command->id);

        if (!$advertisement->password()->isValidatedWith($command->password)) {
            throw new Exception('Invalid password');
        }

        $advertisement->update(
            $command->description,
            new Email($command->email),
            Password::fromPlainPassword($command->password),
        );

        $this->advertisementRepository->save($advertisement);
    }
}
