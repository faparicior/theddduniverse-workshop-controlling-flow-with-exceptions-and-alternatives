package advertisement.domain

import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId

interface AdvertisementRepository {
    fun save(advertisement: Advertisement):  Result<Advertisement>
    fun findById(id: AdvertisementId): Result<Any>
}