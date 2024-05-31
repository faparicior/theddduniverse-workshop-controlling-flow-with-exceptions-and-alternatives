package advertisement.ui.http

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
            return processResult(result)
        } catch (e: Exception) {
            return processGenericException(e)
        }
    }
}