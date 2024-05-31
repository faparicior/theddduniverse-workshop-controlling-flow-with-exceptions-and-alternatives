package advertisement.domain.errors

import common.domain.DomainError

open class EmailError(val errorMessage: String): DomainError(errorMessage) {
    class InvalidEmailFormat private constructor(val value: String, errorMessage: String) : EmailError(errorMessage) {
        companion object {
            fun withEmail(value: String): InvalidEmailFormat {
                val errorMessage = "Invalid email format $value"

                return InvalidEmailFormat(value, errorMessage)
            }
        }
    }
}
