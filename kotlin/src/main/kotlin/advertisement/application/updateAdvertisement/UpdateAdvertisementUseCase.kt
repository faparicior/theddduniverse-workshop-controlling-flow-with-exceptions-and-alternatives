package advertisement.application.updateAdvertisement

import advertisement.application.errors.PasswordDoesNotMatchError
import advertisement.domain.AdvertisementRepository
import advertisement.domain.errors.AdvertisementNotFoundError
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Description
import advertisement.domain.model.value_object.Password
import arrow.core.Either
import arrow.core.raise.either
import common.BoundedContextError

class UpdateAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(updateAdvertisementCommand: UpdateAdvertisementCommand): Either<BoundedContextError, Unit> {
        return either {
            val advertisementId = AdvertisementId.build(updateAdvertisementCommand.id).bind()
            val description = Description.build(updateAdvertisementCommand.description).bind()
            val advertisement = getAdvertisement(advertisementId).bind()

            validatePassword(advertisement, updateAdvertisementCommand.password).bind()

            val password = Password.fromPlainPassword(updateAdvertisementCommand.password).bind()
            val updatedAdvertisement = advertisement.update(description, password).bind()

            advertisementRepository.save(updatedAdvertisement).bind()
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
