package advertisement.application.renewAdvertisement

import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Password

class RenewAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(renewAdvertisementCommand: RenewAdvertisementCommand) {
        val advertisementId = AdvertisementId(renewAdvertisementCommand.id)
        val advertisement = advertisementRepository.findById(advertisementId)

        if (null === advertisement) {
            throw NoSuchElementException("Advertisement not found with Id ${advertisementId.value()}")
        }

        if (!advertisement.password.isValidatedWith(renewAdvertisementCommand.password))
            throw IllegalArgumentException("Password does not match")

        advertisement.renew(Password.fromPlainPassword(renewAdvertisementCommand.password))

        advertisementRepository.save(advertisement)
    }
}
