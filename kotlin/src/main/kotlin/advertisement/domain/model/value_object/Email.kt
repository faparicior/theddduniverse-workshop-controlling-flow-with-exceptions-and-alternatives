package advertisement.domain.model.value_object

import advertisement.domain.exceptions.InvalidEmailException

class Email private constructor (private val value: String) {

    companion object {
        fun build(value: String): Result<Email> {
            if (!validate(value)) {
                return Result.failure(InvalidEmailException.withEmail(value))
            }
            return Result.success(Email(value))
        }

        private fun validate(value: String): Boolean {
            val regex = "^[\\w-\\.]+@([\\w-]+\\.)+[\\w-]{2,4}\$"

            return value.matches(regex.toRegex())
        }
    }

    fun value(): String {
        return value
    }
}
