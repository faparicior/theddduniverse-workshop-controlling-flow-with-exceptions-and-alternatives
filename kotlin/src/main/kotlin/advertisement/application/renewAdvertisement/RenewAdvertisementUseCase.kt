package advertisement.application.renewAdvertisement

import advertisement.application.errors.PasswordDoesNotMatchError
import advertisement.domain.AdvertisementRepository
import advertisement.domain.errors.AdvertisementNotFoundError
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Password
import arrow.core.Either
import arrow.core.raise.either
import common.BoundedContextError

class RenewAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(renewAdvertisementCommand: RenewAdvertisementCommand): Either<BoundedContextError, Unit> {
        return either {
            val advertisementId = AdvertisementId.build(renewAdvertisementCommand.id).bind()
            val advertisement = getAdvertisement(advertisementId).bind()

            validatePassword(advertisement, renewAdvertisementCommand.password).bind()

            val password = Password.fromPlainPassword(renewAdvertisementCommand.password).bind()
            val renewedAdvertisement = advertisement.renew(password).bind()

            advertisementRepository.save(renewedAdvertisement).bind()
        }
    }

    private fun getAdvertisement(advertisementId: AdvertisementId): Either<BoundedContextError, Advertisement> {
        val advertisement = advertisementRepository.findById(advertisementId)
        if (advertisement.isLeft()) {
            return Either.Left(AdvertisementNotFoundError.withId(advertisementId.value()))
        }

        return Either.Right(advertisement.getOrNull()!!)
    }

    private fun validatePassword(advertisement: Advertisement, password: String): Either<BoundedContextError, Unit> {
        if (advertisement.password.isValidatedWith(password).not())
            return Either.Left(PasswordDoesNotMatchError.build())

        return Either.Right(Unit)
    }
}
