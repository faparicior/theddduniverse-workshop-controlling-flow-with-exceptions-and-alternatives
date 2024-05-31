package advertisement.domain

import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import arrow.core.Either
import common.BoundedContextError

interface AdvertisementRepository {
    fun save(advertisement: Advertisement):  Either<BoundedContextError, Advertisement>
    fun findById(id: AdvertisementId): Either<BoundedContextError, Advertisement>
}