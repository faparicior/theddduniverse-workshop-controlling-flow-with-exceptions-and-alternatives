package advertisement.domain.model.value_object

import advertisement.domain.errors.EmailError
import arrow.core.Either
import arrow.core.left
import arrow.core.right

class Email private constructor (private val value: String) {

    fun value(): String = value
    companion object {
        fun build(value: String): Either<EmailError, Email> {
            if (!validate(value)) {
                return EmailError.InvalidEmailFormat.withEmail(value).left()
            }
            return Email(value).right()
        }

        private fun validate(value: String): Boolean {
            val regex = "^[\\w-\\.]+@([\\w-]+\\.)+[\\w-]{2,4}\$"

            return value.matches(regex.toRegex())
        }
    }
}
