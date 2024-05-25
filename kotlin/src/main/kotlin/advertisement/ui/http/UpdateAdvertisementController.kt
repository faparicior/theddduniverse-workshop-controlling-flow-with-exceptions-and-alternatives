package advertisement.ui.http

import advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisement.application.updateAdvertisement.UpdateAdvertisementCommand
import advertisement.application.updateAdvertisement.UpdateAdvertisementUseCase
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse

class UpdateAdvertisementController(private val useCase: UpdateAdvertisementUseCase): CommonController(){

    fun execute(request: FrameworkRequest): FrameworkResponse {
        try {
            val result = useCase.execute(
                UpdateAdvertisementCommand(
                    request.getIdPath(),
                    request.content["description"]!!,
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