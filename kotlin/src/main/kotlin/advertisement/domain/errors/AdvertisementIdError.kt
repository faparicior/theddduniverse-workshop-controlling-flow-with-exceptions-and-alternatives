package advertisement.domain.errors

import common.domain.DomainError

open class AdvertisementIdError(val errorMessage: String): DomainError(errorMessage) {
    class InvalidUniqueIdIdentifier private constructor(val value: String, errorMessage: String) : AdvertisementIdError(errorMessage) {
        companion object {
            fun withId(value: String): InvalidUniqueIdIdentifier {
                return InvalidUniqueIdIdentifier(value, "Invalid unique identifier format for $value")
            }
        }
    }
}
