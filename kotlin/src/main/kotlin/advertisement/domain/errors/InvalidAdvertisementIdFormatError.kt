package advertisement.domain.errors

import common.domain.DomainError

open class InvalidAdvertisementIdFormatError private constructor(errorMessage: String): DomainError(errorMessage) {
     companion object {
        fun withId(value: String): InvalidAdvertisementIdFormatError {
            return InvalidAdvertisementIdFormatError("Invalid unique identifier format for $value")
        }
    }
}
