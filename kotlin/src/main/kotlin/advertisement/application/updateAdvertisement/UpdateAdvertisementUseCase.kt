package advertisement.application.updateAdvertisement

import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Description
import advertisement.domain.model.value_object.Password

class UpdateAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(updateAdvertisementCommand: UpdateAdvertisementCommand) {
        val advertisementId = AdvertisementId(updateAdvertisementCommand.id)
        val advertisement = advertisementRepository.findById(advertisementId)

        if (null === advertisement) {
            throw NoSuchElementException("Advertisement not found with Id ${advertisementId.value()}")
        }

        if (!advertisement.password.isValidatedWith(updateAdvertisementCommand.password))
            throw IllegalArgumentException("Invalid password")

        advertisement.update(
            Description(updateAdvertisementCommand.description),
            Password.fromPlainPassword(updateAdvertisementCommand.password)
        )

        advertisementRepository.save(advertisement)
    }
}
