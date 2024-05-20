<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain;

use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Common\Result;

interface AdvertisementRepository
{
    public function save(Advertisement $advertisement): Result;

    public function findById(AdvertisementId $id): Result;
}
