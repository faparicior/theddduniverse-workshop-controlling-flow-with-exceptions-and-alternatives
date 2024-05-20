<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Infrastructure\Persistence;

use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementDate;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Description;
use Demo\App\Advertisement\Domain\Model\ValueObject\Email;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Demo\App\Common\Result;
use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\database\SqliteConnection;

class SqliteAdvertisementRepository implements AdvertisementRepository
{
    private DatabaseConnection $dbConnection;
    public function __construct(SqliteConnection $connection)
    {
        $this->dbConnection = $connection;
    }

    public function save(Advertisement $advertisement): Result
    {
        try {
            $this->dbConnection->execute(sprintf('
            INSERT INTO advertisements (id, description, email, password, advertisement_date) VALUES (\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\') 
            ON CONFLICT(id) DO UPDATE SET description = \'%2$s\', email = \'%3$s\', password = \'%4$s\', advertisement_date = \'%5$s\';',
                    $advertisement->id()->value(),
                    $advertisement->description()->value(),
                    $advertisement->email()->value(),
                    $advertisement->password()->value(),
                    $advertisement->date()->value()->format('Y-m-d H:i:s')
                )
            );
        } catch (\Throwable $exception)
        {
            return Result::failure($exception->getMessage());
        }

        return Result::success();
    }

    public function findById(AdvertisementId $id): Result
    {
        $result = $this->dbConnection->query(sprintf('SELECT * FROM advertisements WHERE id = \'%s\'', $id->value()));
        if(!$result) {
            return Result::failure('Advertisement not found');
        }

        $row = $result[0];

        $result = AdvertisementId::build($row['id']);
        if ($result->isError()) {
            return $result;
        }
        /** @var AdvertisementId $advertisementId */
        $advertisementId = $result->unwrap();

        $result = Description::build($row['description']);
        if ($result->isError()) {
            return $result;
        }
        /** @var Description $description */
        $description = $result->unwrap();

        $result = Email::build($row['email']);
        if ($result->isError()) {
            return $result;
        }
        /** @var Email $email */
        $email = $result->unwrap();

        $result = Password::fromEncryptedPassword($row['password']);
        if ($result->isError()) {
            return $result;
        }
        /** @var Password $password */
        $password = $result->unwrap();

        $result = AdvertisementDate::build(new \DateTime($row['advertisement_date']));
        if ($result->isError()) {
            return $result;
        }

        /** @var AdvertisementDate $date */
        $date = $result->unwrap();

        return Advertisement::build(
            $advertisementId,
            $description,
            $email,
            $password,
            $date,
        );
    }
}
