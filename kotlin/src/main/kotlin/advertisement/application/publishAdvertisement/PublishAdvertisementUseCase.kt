package advertisement.application.publishAdvertisement

import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.Advertisement
import advertisement.domain.model.value_object.AdvertisementDate
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Description
import advertisement.domain.model.value_object.Password
import java.time.LocalDateTime

class PublishAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(publishAdvertisementCommand: PublishAdvertisementCommand) {
        val advertisement = Advertisement(
            AdvertisementId(publishAdvertisementCommand.id),
            Description(publishAdvertisementCommand.description),
            Password.fromPlainPassword(publishAdvertisementCommand.password),
            AdvertisementDate(LocalDateTime.now())
        )

        advertisementRepository.save(advertisement)
    }
}
