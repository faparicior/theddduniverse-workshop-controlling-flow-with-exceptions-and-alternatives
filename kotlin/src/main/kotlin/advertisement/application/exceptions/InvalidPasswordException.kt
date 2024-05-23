package advertisement.application.exceptions

import common.application.ApplicationException

class InvalidPasswordException private constructor(message: String) : ApplicationException(message) {

    companion object {
        fun build(): InvalidPasswordException {
            return InvalidPasswordException("Invalid password")
        }
    }
}
