package advertisement.application.updateAdvertisement

import advertisement.application.exceptions.PasswordDoesNotMatchException
import advertisement.domain.AdvertisementRepository
import advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Description
import advertisement.domain.model.value_object.Password
import advertisement.infrastructure.exceptions.ZeroRecordsException

class UpdateAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(updateAdvertisementCommand: UpdateAdvertisementCommand): Result<Any> = runCatching {
        val advertisementId = AdvertisementId.build(updateAdvertisementCommand.id).getOrThrow()
        val description = Description.build(updateAdvertisementCommand.description).getOrThrow()

        var advertisement: Advertisement = getAdvertisement(advertisementId).getOrThrow()
        
        validatePassword(advertisement, updateAdvertisementCommand.password).getOrThrow()

        val password = Password.fromPlainPassword(updateAdvertisementCommand.password).getOrThrow()
        advertisement = advertisement.update(description, password).getOrThrow()

        return advertisementRepository.save(advertisement)
    }

    private fun getAdvertisement(advertisementId: AdvertisementId): Result<Advertisement> {
        try {
            val advertisement = advertisementRepository.findById(advertisementId).getOrThrow() as Advertisement
            return Result.success(advertisement)
        } catch (e: ZeroRecordsException) {
            return Result.failure(AdvertisementNotFoundException.withId(advertisementId.value()))
        }
    }

    private fun validatePassword(advertisement: Advertisement, password: String): Result<Unit>{
        if (advertisement.password.isValidatedWith(password).not())
            return Result.failure(PasswordDoesNotMatchException.build())

        return Result.success(Unit)
    }
}
