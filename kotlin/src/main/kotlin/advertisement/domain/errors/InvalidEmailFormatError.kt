package advertisement.domain.errors

import common.domain.DomainError

open class InvalidEmailFormatError private constructor(errorMessage: String): DomainError(errorMessage) {
    companion object {
        fun withEmail(value: String): InvalidEmailFormatError {
            val errorMessage = "Invalid email format $value"

            return InvalidEmailFormatError(errorMessage)
        }
    }
}
