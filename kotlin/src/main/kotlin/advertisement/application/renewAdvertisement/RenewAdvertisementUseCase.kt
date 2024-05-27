package advertisement.application.renewAdvertisement

import advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisement.application.exceptions.PasswordDoesNotMatchException
import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Password
import advertisement.infrastructure.exceptions.ZeroRecordsException

class RenewAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(renewAdvertisementCommand: RenewAdvertisementCommand): Result<Any>{
        val advertisementId = AdvertisementId.build(renewAdvertisementCommand.id).map { it }.fold(
            onSuccess = { it },
            onFailure = { return Result.failure(it) }
        )

        val advertisement = advertisementRepository.findById(advertisementId).map { it as Advertisement }.fold(
            onSuccess = { it },
            onFailure = {
                if  (it is ZeroRecordsException) return Result.failure(AdvertisementNotFoundException.withId(advertisementId.value()))
                return Result.failure(it)
            }
        )

//        validatePassword(advertisement, renewAdvertisementCommand.password).map { it }.fold(
//            onSuccess = {  },
//            onFailure = { return Result.failure(it) }
//        )

        if (!advertisement.password.isValidatedWith(renewAdvertisementCommand.password))
            return Result.failure(PasswordDoesNotMatchException.build())

        val password: Password = Password.fromPlainPassword(renewAdvertisementCommand.password).map { it }.fold(
            onSuccess = { it },
            onFailure = { return Result.failure(it) }
        )

        advertisement.renew(password)

        return advertisementRepository.save(advertisement)
    }

//    private fun validatePassword(advertisement: Advertisement, password: String): Result<Any> {
//        return advertisement.password.isValidatedWithResult(password).map { it }.fold(
//            onSuccess = { Result.success(it) },
//            onFailure = { Result.failure(it) }
//        )
//    }
}
