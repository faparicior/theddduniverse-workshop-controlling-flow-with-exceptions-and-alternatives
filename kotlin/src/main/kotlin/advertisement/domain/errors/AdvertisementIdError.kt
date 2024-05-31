package advertisement.domain.errors

import common.domain.DomainError

open class AdvertisementIdError(val errorMessage: String): DomainError(errorMessage) {
    class InvalidFormat private constructor(val value: String, errorMessage: String) : AdvertisementIdError(errorMessage) {
        companion object {
            fun withId(value: String): InvalidFormat {
                return InvalidFormat(value, "Invalid unique identifier format for $value")
            }
        }
    }
}
