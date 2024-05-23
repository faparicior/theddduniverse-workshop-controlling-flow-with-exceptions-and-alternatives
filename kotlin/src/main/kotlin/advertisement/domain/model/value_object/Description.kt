package advertisement.domain.model.value_object

import advertisement.domain.exceptions.DescriptionEmptyException
import advertisement.domain.exceptions.DescriptionTooLongException

class Description private constructor (private val value: String) {

    companion object {
        fun build(value: String): Result<Description> {
            if (value.isEmpty()) {
                return Result.failure(DescriptionEmptyException.build())
            }

            if (value.length > 200) {
                return Result.failure(DescriptionTooLongException.withLongitudeMessage(value))
            }

            return Result.success(Description(value))
        }
    }

    fun value(): String {
        return value
    }
}
