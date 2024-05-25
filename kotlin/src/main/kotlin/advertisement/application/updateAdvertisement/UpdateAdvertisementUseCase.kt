package advertisement.application.updateAdvertisement

import advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisement.application.exceptions.InvalidPasswordException
import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Description
import advertisement.domain.model.value_object.Password
import advertisement.infrastructure.exceptions.ZeroRecordsException

class UpdateAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(updateAdvertisementCommand: UpdateAdvertisementCommand): Result<Any> {
        val descriptionResult = Description.build(updateAdvertisementCommand.description)
        if (descriptionResult.isFailure) {
            return descriptionResult
        }

        val advertisementResult = getAdvertisement(updateAdvertisementCommand)
        if (advertisementResult.isFailure) {
            return advertisementResult
        }
        val advertisement: Advertisement = advertisementResult.getOrThrow() as Advertisement

        if (!advertisement.password.isValidatedWith(updateAdvertisementCommand.password))
            return Result.failure(InvalidPasswordException.build())

        val passwordResult = Password.fromPlainPassword(updateAdvertisementCommand.password)
        if (passwordResult.isFailure) {
            return passwordResult
        }

        val updateResult = advertisement.update(descriptionResult.getOrThrow(), passwordResult.getOrThrow())
        if (updateResult.isFailure) {
            return updateResult
        }

        return advertisementRepository.save(advertisement)
    }

    private fun getAdvertisement(updateAdvertisementCommand: UpdateAdvertisementCommand): Result<Any>{
        val advertisementIdResult = AdvertisementId.build(updateAdvertisementCommand.id)

        if (advertisementIdResult.isFailure) {
            return advertisementIdResult
        }
        val advertisementId = advertisementIdResult.getOrThrow()

        val advertisementResult = advertisementRepository.findById(advertisementId)
        if (advertisementResult.isFailure) {
            if  (advertisementResult.exceptionOrNull() is ZeroRecordsException)
                return Result.failure(AdvertisementNotFoundException.withId(advertisementId.value()))
        }

        return advertisementResult
    }
}
