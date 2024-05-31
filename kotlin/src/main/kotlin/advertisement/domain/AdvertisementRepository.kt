package advertisement.domain

import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import arrow.core.Either

interface AdvertisementRepository {
    fun save(advertisement: Advertisement):  Either<Any, Advertisement>
    fun findById(id: AdvertisementId): Either<Any, Advertisement>
}