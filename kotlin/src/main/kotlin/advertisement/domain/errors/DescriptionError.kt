package advertisement.domain.errors

import common.domain.DomainError

open class DescriptionError(val errorMessage: String): DomainError(errorMessage) {
    class Empty (val value: String): DescriptionError("Description empty")
    class TooLong private constructor(val value: String, errorMessage: String) : DescriptionError(errorMessage) {
        companion object {
            fun withLongitudeMessage(value: String): TooLong {
                return TooLong(value, "Description has more than 200 characters: Has ${value.length}")
            }
        }
    }
}
