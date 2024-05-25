package advertisement.application.renewAdvertisement

import advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisement.application.exceptions.InvalidPasswordException
import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Password
import advertisement.infrastructure.exceptions.ZeroRecordsException

class RenewAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(renewAdvertisementCommand: RenewAdvertisementCommand): Result<Any>{
        val advertisementIdResult = AdvertisementId.build(renewAdvertisementCommand.id)

        if (advertisementIdResult.isFailure) {
            return advertisementIdResult
        }
        val advertisementId = advertisementIdResult.getOrThrow()

        val advertisementResult = advertisementRepository.findById(advertisementId)
        if (advertisementResult.isFailure) {
            if  (advertisementResult.exceptionOrNull() is ZeroRecordsException)
                return Result.failure(AdvertisementNotFoundException.withId(advertisementId.value()))

            return advertisementResult
        }

        val advertisement: Advertisement = advertisementResult.getOrThrow() as Advertisement

        if (!advertisement.password.isValidatedWith(renewAdvertisementCommand.password))
            return Result.failure(InvalidPasswordException.build())

        val passwordResult = Password.fromPlainPassword(renewAdvertisementCommand.password)
        if (passwordResult.isFailure) {
            return passwordResult
        }

        advertisement.renew(passwordResult.getOrThrow())

        return advertisementRepository.save(advertisement)
    }
}
