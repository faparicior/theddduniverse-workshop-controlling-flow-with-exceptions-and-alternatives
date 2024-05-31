package advertisement.domain.model

import advertisement.domain.model.value_object.AdvertisementDate
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Description
import advertisement.domain.model.value_object.Password
import arrow.core.Either
import arrow.core.flatMap
import common.BoundedContextError
import java.time.LocalDateTime

class Advertisement private constructor(val id: AdvertisementId, var description: Description, var password: Password, var date: AdvertisementDate)
{
    // We use flatmap instead of Bind to show the difference

    companion object
    {
        fun build(id: String, description: String, password: Password, date: LocalDateTime): Either<BoundedContextError, Advertisement>
        {
            return AdvertisementId.build(id).flatMap {
                advertisementId -> Description.build(description).flatMap {
                    description -> AdvertisementDate.build(date).map {
                        advertisementDate -> Advertisement(advertisementId, description, password, advertisementDate)
                    }
                }
            }
        }
    }

    fun update(description: Description, password: Password): Either<BoundedContextError, Advertisement> {
        this.description = description
        this.password = password

        return updateDate().map {
            this
        }
    }

    fun renew(password: Password): Either<BoundedContextError, Advertisement>
    {
        this.password = password

        return updateDate().map {
            this
        }
    }

    private fun updateDate():  Either<BoundedContextError, AdvertisementDate>
    {
        return AdvertisementDate.build(LocalDateTime.now()).map {
            this.date = it
            it
        }
    }
}
