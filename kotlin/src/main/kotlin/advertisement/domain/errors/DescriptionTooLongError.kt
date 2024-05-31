package advertisement.domain.errors

import common.domain.DomainError

class DescriptionTooLongError private constructor(errorMessage: String): DomainError(errorMessage) {
    companion object {
        fun withLongitudeMessage(value: String): DescriptionTooLongError {
            return DescriptionTooLongError("Description has more than 200 characters: Has ${value.length}")
        }
    }
}
