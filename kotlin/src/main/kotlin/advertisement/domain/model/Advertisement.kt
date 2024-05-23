package advertisement.domain.model

import advertisement.domain.model.value_object.AdvertisementDate
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Description
import advertisement.domain.model.value_object.Password
import java.time.LocalDateTime

class Advertisement private constructor(val id: AdvertisementId, var description: Description, var password: Password, var date: AdvertisementDate)
{

    companion object
    {
        fun build(id: String, description: String, password: Password, date: LocalDateTime): Result<Advertisement>
        {
            val advertisementIdResult = AdvertisementId.build(id)
            if (advertisementIdResult.isFailure)
                return Result.failure(advertisementIdResult.exceptionOrNull()!!)

            val descriptionResult = Description.build(description)
            if (descriptionResult.isFailure)
                return Result.failure(descriptionResult.exceptionOrNull()!!)

            val advertisementDateResult = AdvertisementDate.build(date)
            if (advertisementDateResult.isFailure)
                return Result.failure(advertisementDateResult.exceptionOrNull()!!)

            return Result.success(
                Advertisement(
                    advertisementIdResult.getOrThrow(),
                    descriptionResult.getOrThrow(),
                    password,
                    advertisementDateResult.getOrThrow(),
                )
            )
        }
    }

    fun update(description: Description, password: Password):  Result<Advertisement>
    {
        this.description = description
        this.password = password

        val result = updateDate()
        if (result.isFailure)
            return Result.failure(result.exceptionOrNull()!!)

        return Result.success(this)
    }

    fun renew(password: Password): Result<Advertisement>
    {
        this.password = password

        val result = updateDate()
        if (result.isFailure)
            return Result.failure(result.exceptionOrNull()!!)

        return Result.success(this)
    }

    private fun updateDate():  Result<Advertisement>
    {
        val result = AdvertisementDate.build(LocalDateTime.now())

        if (result.isFailure)
            return Result.failure(result.exceptionOrNull()!!)
        this.date = result.getOrThrow()

        return Result.success(this)
    }
}
