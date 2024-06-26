package advertisement.ui.http

import advertisement.application.updateAdvertisement.UpdateAdvertisementCommand
import advertisement.application.updateAdvertisement.UpdateAdvertisementUseCase
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse
import java.util.NoSuchElementException

class UpdateAdvertisementController(private val useCase: UpdateAdvertisementUseCase): CommonController(){

    fun execute(request: FrameworkRequest): FrameworkResponse {
        try {
            useCase.execute(
                UpdateAdvertisementCommand(
                    request.getIdPath(),
                    request.content["description"]!!,
                    request.content["password"]!!,
                )
            )

            return processSuccessfulCommand()
        } catch (e: NoSuchElementException) {
            return processNotFoundCommand(e)
        } catch (e: Exception) {
            return processGenericException(e)
        }
    }
}