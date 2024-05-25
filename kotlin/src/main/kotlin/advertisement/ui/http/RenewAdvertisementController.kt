package advertisement.ui.http

import advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisement.application.renewAdvertisement.RenewAdvertisementCommand
import advertisement.application.renewAdvertisement.RenewAdvertisementUseCase
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse

class RenewAdvertisementController(private val useCase: RenewAdvertisementUseCase): CommonController() {

    fun execute(request: FrameworkRequest): FrameworkResponse {
        try {
            val result = useCase.execute(
                RenewAdvertisementCommand(
                    request.getIdPath(),
                    request.content["password"]!!,
                )
            )
            if (result.isFailure) {
                if (result.exceptionOrNull() is AdvertisementNotFoundException) {
                    return processNotFoundCommand(result.exceptionOrNull()!!)
                }
                return processApplicationOrDomainException(result.exceptionOrNull()!!)
            }

            return processSuccessfulCommand()
        } catch (e: Exception) {
            return processGenericException(e)
        }
    }
}