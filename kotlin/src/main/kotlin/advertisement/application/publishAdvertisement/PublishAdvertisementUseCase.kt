package advertisement.application.publishAdvertisement

import advertisement.domain.AdvertisementRepository
import advertisement.domain.errors.AdvertisementAlreadyExistsError
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Password
import arrow.core.Either
import arrow.core.raise.either
import common.BoundedContextError
import java.time.LocalDateTime

class PublishAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(publishAdvertisementCommand: PublishAdvertisementCommand): Either<BoundedContextError, Unit> {
        return either {
            val advertisementId = AdvertisementId.build(publishAdvertisementCommand.id).bind()
            ensureThatAdvertisementIsUnique(advertisementId).bind()

            val password = Password.fromPlainPassword(publishAdvertisementCommand.password).bind()

            val advertisement = Advertisement.build(
                publishAdvertisementCommand.id,
                publishAdvertisementCommand.description,
                password,
                LocalDateTime.now()
            ).bind()

            advertisementRepository.save(advertisement).bind()
        }
    }

    private fun ensureThatAdvertisementIsUnique(advertisementId: AdvertisementId): Either<BoundedContextError, Unit> {
        return advertisementRepository.findById(advertisementId).fold(
            { Either.Right(Unit) },
            { Either.Left(AdvertisementAlreadyExistsError.withId(advertisementId.value())) } // If Right (success), return Left (error)
        )
    }
}
