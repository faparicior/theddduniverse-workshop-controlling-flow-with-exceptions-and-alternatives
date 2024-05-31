package advertisement.domain.errors

import common.domain.EntityNotFoundDomainError

open class AdvertisementNotFoundError private constructor(errorMessage: String): EntityNotFoundDomainError(errorMessage) {
    companion object {
        fun withId(value: String): AdvertisementNotFoundError {
            val errorMessage = "Advertisement not found with Id $value"

            return AdvertisementNotFoundError(errorMessage)
        }
    }
}
