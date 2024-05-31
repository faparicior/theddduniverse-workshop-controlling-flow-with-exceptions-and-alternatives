package advertisement.application.renewAdvertisement

import advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisement.application.exceptions.PasswordDoesNotMatchException
import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Password
import arrow.core.Either
import arrow.core.raise.either

class RenewAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(renewAdvertisementCommand: RenewAdvertisementCommand): Either<Any, Any> {
        return either {
            val advertisementId = AdvertisementId.build(renewAdvertisementCommand.id).bind()
            val advertisement = getAdvertisement(advertisementId).bind()

            validatePassword(advertisement, renewAdvertisementCommand.password).bind()

            val password = Password.fromPlainPassword(renewAdvertisementCommand.password).bind()
            val renewedAdvertisement = advertisement.renew(password).bind()

            advertisementRepository.save(renewedAdvertisement).bind()
        }
    }

    private fun getAdvertisement(advertisementId: AdvertisementId): Either<AdvertisementNotFoundException, Advertisement> {
        val advertisement = advertisementRepository.findById(advertisementId)
        if (advertisement.isLeft()) {
            return Either.Left(AdvertisementNotFoundException.withId(advertisementId.value()))
        }

        return Either.Right(advertisement.orNull()!!)
    }

    private fun validatePassword(advertisement: Advertisement, password: String): Either<PasswordDoesNotMatchException, Unit> {
        if (advertisement.password.isValidatedWith(password).not())
            return Either.Left(PasswordDoesNotMatchException.build())

        return Either.Right(Unit)
    }
}
