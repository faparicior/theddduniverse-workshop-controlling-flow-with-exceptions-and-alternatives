package advertisement.domain.model.value_object

import arrow.core.Either
import arrow.core.right
import java.time.LocalDateTime

class AdvertisementDate private constructor(private val value: LocalDateTime) {

    companion object {
        fun build(value: LocalDateTime): Either<Unit, AdvertisementDate> {
            return AdvertisementDate(value).right()
        }
    }

    fun value(): LocalDateTime {
        return value
    }
}
