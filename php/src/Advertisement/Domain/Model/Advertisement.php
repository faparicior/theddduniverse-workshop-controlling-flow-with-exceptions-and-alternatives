<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model;

use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementDate;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Description;
use Demo\App\Advertisement\Domain\Model\ValueObject\Email;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Demo\App\Common\Result;

final class Advertisement
{
    private function __construct(
        private readonly AdvertisementId $id,
        private Description $description,
        private Email $email,
        private Password $password,
        private AdvertisementDate $date
    ) {}

    public static function build(string $id, string $description, string $email, Password $password, \DateTime $date): Result
    {
        $advertisementIdResult = AdvertisementId::build($id);
        if ($advertisementIdResult->isFailure()) {
            return $advertisementIdResult;
        }
        /** @var AdvertisementId $advertisementId */
        $advertisementId = $advertisementIdResult->getOrThrow();

        $descriptionResult = Description::build($description);
        if ($descriptionResult->isFailure()) {
            return $descriptionResult;
        }
        /** @var Description $description */
        $description = $descriptionResult->getOrThrow();

        $emailResult = Email::build($email);
        if ($emailResult->isFailure()) {
            return $emailResult;
        }
        /** @var Email $email */
        $email = $emailResult->getOrThrow();

        $advertisementDateResult = AdvertisementDate::build($date);
        if ($advertisementDateResult->isFailure()) {
            return $advertisementDateResult;
        }

        /** @var AdvertisementDate $date */
        $date = $advertisementDateResult->getOrThrow();

        return Result::success(new self($advertisementId, $description, $email, $password, $date));
    }

    public function renew(Password $password): Result
    {
        $this->password = $password;
        $result = $this->updateDate();

        if ($result->isFailure()) {
            return $result;
        }

        return Result::success($this);
    }

    public function update(string $description, string $email, Password $password): Result
    {
        $descriptionResult = Description::build($description);
        if ($descriptionResult->isFailure()) {
            return $descriptionResult;
        }
        /** @var Description $description */
        $description = $descriptionResult->getOrThrow();

        $emailResult = Email::build($email);
        if ($emailResult->isFailure()) {
            return $emailResult;
        }
        /** @var Email $email */
        $email = $emailResult->getOrThrow();

        $this->description = $description;
        $this->email = $email;
        $this->password = $password;
        $result = $this->updateDate();

        if ($result->isFailure()) {
            return $result;
        }

        return Result::success($this);
    }

    public function id(): AdvertisementId
    {
        return $this->id;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function date(): AdvertisementDate
    {
        return $this->date;
    }

    private function updateDate(): Result
    {
        $result = AdvertisementDate::build(new \DateTime());
        if ($result->isFailure()) {
            return $result;
        }

        /** @var AdvertisementDate $date */
        $date = $result->getOrThrow();
        $this->date = $date;

        return Result::success();
    }
}
