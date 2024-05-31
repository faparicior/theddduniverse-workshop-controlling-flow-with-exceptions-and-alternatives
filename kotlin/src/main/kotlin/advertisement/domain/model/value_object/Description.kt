package advertisement.domain.model.value_object

import advertisement.domain.errors.DescriptionEmptyError
import advertisement.domain.errors.DescriptionTooLongError
import arrow.core.Either
import arrow.core.left
import arrow.core.right
import common.domain.DomainError

class Description private constructor (private val value: String) {

    companion object {
        fun build(value: String): Either<DomainError, Description> =
            when {
                value.isEmpty() -> DescriptionEmptyError.build(value).left()
                value.length > 200 -> DescriptionTooLongError.withLongitudeMessage(value).left()
                else -> Description(value).right()
            }
    }

    fun value(): String = value
}
