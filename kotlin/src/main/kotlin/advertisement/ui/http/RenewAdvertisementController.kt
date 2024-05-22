package advertisement.ui.http

import advertisement.application.renewAdvertisement.RenewAdvertisementCommand
import advertisement.application.renewAdvertisement.RenewAdvertisementUseCase
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse
import java.util.NoSuchElementException

class RenewAdvertisementController(private val useCase: RenewAdvertisementUseCase): CommonController() {

    fun execute(request: FrameworkRequest): FrameworkResponse {
        try {
            useCase.execute(
                RenewAdvertisementCommand(
                    request.getIdPath(),
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