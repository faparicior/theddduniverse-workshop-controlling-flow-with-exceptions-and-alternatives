<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Infrastructure\Persistence;

use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Demo\App\Advertisement\Infrastructure\Errors\ZeroRecordsError;
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

    public function save(Advertisement $advertisement): void
    {
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
    }

    public function findById(AdvertisementId $id): Result
    {
        $result = $this->dbConnection->query(sprintf('SELECT * FROM advertisements WHERE id = \'%s\'', $id->value()));
        if(!$result) {
            return ZeroRecordsError::build($id->value());
        }

        $row = $result[0];

        $passwordResult = Password::fromEncryptedPassword($row['password']);
        if ($passwordResult->isError()) {
            return $passwordResult;
        }
        /** @var Password $password */
        $password = $passwordResult->unwrap();

        return Advertisement::build(
            $row['id'],
            $row['description'],
            $row['email'],
            $password,
            new \DateTime($row['advertisement_date']),
        );
    }
}
