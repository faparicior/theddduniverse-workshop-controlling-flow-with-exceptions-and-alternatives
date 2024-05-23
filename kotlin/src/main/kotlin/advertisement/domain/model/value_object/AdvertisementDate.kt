package advertisement.domain.model.value_object

import java.time.LocalDateTime

class AdvertisementDate private constructor(private val value: LocalDateTime) {

    companion object {
        fun build(value: LocalDateTime): Result<AdvertisementDate> {
            return Result.success(AdvertisementDate(value))
        }
    }

    fun value(): LocalDateTime {
        return value
    }
}
