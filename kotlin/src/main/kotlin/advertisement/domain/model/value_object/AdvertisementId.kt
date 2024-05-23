package advertisement.domain.model.value_object

import advertisement.domain.exceptions.InvalidUniqueIdentifierException

class AdvertisementId private constructor (private val value: String) {

    companion object {
        fun build(value: String): Result<AdvertisementId> {
            if (!validate(value)) {
                return Result.failure(InvalidUniqueIdentifierException.withId(value))
            }

            return Result.success(AdvertisementId(value))
        }

        private fun validate(value: String): Boolean {
            val regex = "^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$"

            return value.matches(regex.toRegex())
        }
    }

    fun value(): String {
        return value
    }
}
