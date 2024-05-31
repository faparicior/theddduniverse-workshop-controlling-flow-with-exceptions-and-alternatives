package advertisement.application.errors

import common.application.ApplicationError

open class PasswordDoesNotMatchError private constructor(errorMessage: String): ApplicationError(errorMessage) {
    companion object {
        fun build(): PasswordDoesNotMatchError {
            return PasswordDoesNotMatchError("Password does not match")
        }
    }
}
