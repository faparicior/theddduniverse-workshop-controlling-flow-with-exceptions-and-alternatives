package advertisement.infrastructure.persistence

import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Password
import advertisement.infrastructure.exceptions.ZeroRecordsException
import framework.database.DatabaseConnection
import java.time.LocalDateTime

class SqLiteAdvertisementRepository(private val connection: DatabaseConnection): AdvertisementRepository {
    override fun save(advertisement: Advertisement): Result<Advertisement> {
        val passwordHash = advertisement.password.value()
        connection.execute(
            "INSERT INTO advertisements (id, description, password, advertisement_date) VALUES ('" +
                    "${advertisement.id.value()}', '${advertisement.description.value()}', '$passwordHash', '${advertisement.date.value()}') " +
                    "ON CONFLICT(id) DO UPDATE SET description = '${advertisement.description.value()}', password = '${passwordHash}', advertisement_date = '${advertisement.date.value()}';"
        )

        return Result.success(advertisement)
    }

    override fun findById(id: AdvertisementId): Result<Advertisement> {
        val result = connection.query(
            "SELECT * FROM advertisements WHERE id = '${id.value()}'"
        )

        if (!result.next()) {
            return Result.failure(ZeroRecordsException.withId(id.value()))
        }

        val passwordResult = Password.fromEncryptedPassword(result.getString("password"))
        if (passwordResult.isFailure) {
            return Result.failure(passwordResult.exceptionOrNull()!!)
        }

        val password = passwordResult.getOrThrow()

        return Advertisement.build(
            result.getString("id"),
            result.getString("description"),
            password,
            LocalDateTime.parse(result.getString("advertisement_date")),
        )
    }
}
