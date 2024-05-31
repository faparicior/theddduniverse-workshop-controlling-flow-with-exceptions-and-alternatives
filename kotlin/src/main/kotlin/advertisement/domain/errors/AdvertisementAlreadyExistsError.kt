package advertisement.domain.errors

import common.domain.DomainError

open class AdvertisementAlreadyExistsError private constructor(errorMessage: String): DomainError(errorMessage) {
    companion object {
        fun withId(value: String): AdvertisementAlreadyExistsError {
            val errorMessage = "Advertisement with id $value already exists"

            return AdvertisementAlreadyExistsError(errorMessage)
        }
    }
}
