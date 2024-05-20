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

    public static function build(AdvertisementId $id, Description $description, Email $email, Password $password, AdvertisementDate $date): Result
    {
        return Result::success(new self($id, $description, $email, $password, $date));
    }

    public function renew(Password $password): Result
    {
        $this->password = $password;
        $result = $this->updateDate();

        if ($result->isError()) {
            return $result;
        }

        return Result::success();
    }

    public function update(Description $description, Email $email, Password $password): Result
    {
        $this->description = $description;
        $this->email = $email;
        $this->password = $password;
        $result = $this->updateDate();

        if ($result->isError()) {
            return $result;
        }

        return Result::success();
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
        if ($result->isError()) {
            return $result;
        }

        /** @var AdvertisementDate $date */
        $date = $result->unwrap();
        $this->date = $date;

        return Result::success();
    }
}
