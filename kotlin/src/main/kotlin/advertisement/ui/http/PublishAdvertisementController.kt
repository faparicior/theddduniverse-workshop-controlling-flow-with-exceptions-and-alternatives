package advertisement.ui.http

import advertisement.application.publishAdvertisement.PublishAdvertisementCommand
import advertisement.application.publishAdvertisement.PublishAdvertisementUseCase
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse

class PublishAdvertisementController(private val useCase: PublishAdvertisementUseCase): CommonController(){

    fun execute(request: FrameworkRequest): FrameworkResponse {
        try {
            val result = useCase.execute(
                PublishAdvertisementCommand(
                    request.content["id"]!!,
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
