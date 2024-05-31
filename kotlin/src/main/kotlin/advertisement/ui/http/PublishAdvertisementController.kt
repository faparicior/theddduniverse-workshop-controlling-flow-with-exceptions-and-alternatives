package advertisement.ui.http

import advertisement.application.publishAdvertisement.PublishAdvertisementCommand
import advertisement.application.publishAdvertisement.PublishAdvertisementUseCase
import arrow.core.Either
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
            return when (result) {
                is Either.Left -> processApplicationOrDomainException(result.value)
                is Either.Right -> processSuccessfulCreateCommand()
            }
        } catch (e: Exception) {
            return processGenericException(e)
        }
    }
}
