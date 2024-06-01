<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model;

use Chemem\Bingo\Functional\Functors\Monads\Either;
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

    public static function build(string $id, string $description, string $email, Password $password, \DateTime $date): Either
    {
        $advertisementIdResult = AdvertisementId::build($id);
        if ($advertisementIdResult->isLeft()) {
            return $advertisementIdResult;
        }
        /** @var AdvertisementId $advertisementId */
        $advertisementId = $advertisementIdResult->getRight();

        $descriptionResult = Description::build($description);
        if ($descriptionResult->isLeft()) {
            return $descriptionResult;
        }
        /** @var Description $description */
        $description = $descriptionResult->getRight();

        $emailResult = Email::build($email);
        if ($emailResult->isLeft()) {
            return $emailResult;
        }
        /** @var Email $email */
        $email = $emailResult->getRight();

        $advertisementDateResult = AdvertisementDate::build($date);
        if ($advertisementDateResult->isLeft()) {
            return $advertisementDateResult;
        }

        /** @var AdvertisementDate $date */
        $date = $advertisementDateResult->getRight();

        return Either::right(new self($advertisementId, $description, $email, $password, $date));
    }

    public function renew(Password $password): Either
    {
        $this->password = $password;
        $result = $this->updateDate();

        if ($result->isLeft()) {
            return $result;
        }

        return Either::right($this);
    }

    public function update(string $description, string $email, Password $password): Either
    {
        $descriptionResult = Description::build($description);
        if ($descriptionResult->isLeft()) {
            return $descriptionResult;
        }
        /** @var Description $description */
        $description = $descriptionResult->getRight();

        $emailResult = Email::build($email);
        if ($emailResult->isLeft()) {
            return $emailResult;
        }
        /** @var Email $email */
        $email = $emailResult->getRight();

        $this->description = $description;
        $this->email = $email;
        $this->password = $password;
        $result = $this->updateDate();

        if ($result->isLeft()) {
            return $result;
        }

        return Either::right($this);
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

    private function updateDate(): Either
    {
        $result = AdvertisementDate::build(new \DateTime());
        if ($result->isLeft()) {
            return $result;
        }

        /** @var AdvertisementDate $date */
        $date = $result->getRight();
        $this->date = $date;

        return Either::right(null);
    }
}
