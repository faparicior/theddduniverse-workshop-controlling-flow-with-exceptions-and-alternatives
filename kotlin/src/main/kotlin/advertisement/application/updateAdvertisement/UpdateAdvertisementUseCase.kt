package advertisement.application.updateAdvertisement

import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.value_object.Description
import advertisement.domain.model.value_object.Password

class UpdateAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(updateAdvertisementCommand: UpdateAdvertisementCommand) {
        val advertisement = advertisementRepository.findById(updateAdvertisementCommand.id)

        if (!advertisement.password.isValidatedWith(updateAdvertisementCommand.password))
            throw IllegalArgumentException("Invalid password")

        advertisement.update(
            Description(updateAdvertisementCommand.description),
            Password.fromPlainPassword(updateAdvertisementCommand.password)
        )

        advertisementRepository.save(advertisement)
    }
}