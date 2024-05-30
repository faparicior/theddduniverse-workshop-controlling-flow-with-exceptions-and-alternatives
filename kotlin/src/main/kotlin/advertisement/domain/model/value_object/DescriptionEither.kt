package advertisement.domain.model.value_object

import advertisement.domain.errors.DescriptionError
import arrow.core.Either
import arrow.core.left
import arrow.core.right

class DescriptionEither private constructor (private val value: String) {

    companion object {
        fun build(value: String): Either<DescriptionError, DescriptionEither> =
            when {
                value.isEmpty() -> DescriptionError.Empty(value).left()
                value.length > 200 -> DescriptionError.TooLong.withLongitudeMessage(value).left()
                else -> DescriptionEither(value).right()
            }
    }

    fun value(): String = value
}
