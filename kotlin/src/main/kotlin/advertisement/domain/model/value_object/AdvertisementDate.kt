package advertisement.domain.model.value_object

import arrow.core.Either
import arrow.core.right
import common.BoundedContextError
import java.time.LocalDateTime

class AdvertisementDate private constructor(private val value: LocalDateTime) {

    companion object {
        fun build(value: LocalDateTime): Either<BoundedContextError, AdvertisementDate> {
            return AdvertisementDate(value).right()
        }
    }

    fun value(): LocalDateTime {
        return value
    }
}
