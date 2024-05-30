package advertisement.domain.model.value_object

import advertisement.domain.errors.AdvertisementIdError
import arrow.core.Either
import arrow.core.left
import arrow.core.right
import common.Errors

class AdvertisementId private constructor(private val value: String) {

    companion object {
        private val regex = "^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$".toRegex()

        fun build(value: String): Either<Errors, AdvertisementId> =
            when {
                !value.matches(regex) -> AdvertisementIdError.InvalidUniqueIdIdentifier.withId(value).left()
                else -> AdvertisementId(value).right()
            }
    }

    fun value(): String = value
}
