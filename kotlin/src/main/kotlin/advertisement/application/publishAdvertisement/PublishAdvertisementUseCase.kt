package advertisement.application.publishAdvertisement

import advertisement.domain.exceptions.AdvertisementAlreadyExistsException
import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Password
import arrow.core.Either
import arrow.core.raise.either
import java.time.LocalDateTime

class PublishAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(publishAdvertisementCommand: PublishAdvertisementCommand): Either<Any, Unit> {
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

    private fun ensureThatAdvertisementIsUnique(advertisementId: AdvertisementId): Either<AdvertisementAlreadyExistsException, Unit> {
        if (advertisementRepository.findById(advertisementId).isRight()) {
            return Either.Left(AdvertisementAlreadyExistsException.withId(advertisementId.value()))
        }

        return Either.Right(Unit)
    }
}
