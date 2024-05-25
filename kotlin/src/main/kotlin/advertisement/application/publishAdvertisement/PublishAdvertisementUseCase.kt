package advertisement.application.publishAdvertisement

import advertisement.domain.exceptions.AdvertisementAlreadyExistsException
import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Password
import java.time.LocalDateTime

class PublishAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(publishAdvertisementCommand: PublishAdvertisementCommand): Result<Any> {
        val advertisementIdResult = AdvertisementId.build(publishAdvertisementCommand.id)

        if (advertisementIdResult.isFailure) {
            return advertisementIdResult
        }
        val advertisementId = advertisementIdResult.getOrThrow()

        if (advertisementRepository.findById(advertisementId).isSuccess) {
            return Result.failure(AdvertisementAlreadyExistsException.withId(advertisementId.value()))
        }

        val passwordResult = Password.fromPlainPassword(publishAdvertisementCommand.password)

        if (passwordResult.isFailure) {
            return passwordResult
        }

        val advertisementResult = Advertisement.build(
            advertisementId.value(),
            publishAdvertisementCommand.description,
            passwordResult.getOrThrow(),
            LocalDateTime.now()
        )

        if (advertisementResult.isFailure) {
            return advertisementResult
        }

        return advertisementRepository.save(advertisementResult.getOrThrow())
    }
}
