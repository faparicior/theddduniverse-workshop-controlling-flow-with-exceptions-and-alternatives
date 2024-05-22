package common.ui.http

import framework.FrameworkResponse
import java.lang.Exception

abstract class CommonController {

    protected fun processGenericException(exception: Exception): FrameworkResponse {
        return FrameworkResponse(
            FrameworkResponse.STATUS_BAD_REQUEST,
            mapOf(
                "errors" to exception.message.toString(),
                "code" to FrameworkResponse.STATUS_BAD_REQUEST.toString(),
                "message" to exception.message.toString(),
            ),
        )
    }

    protected fun processSuccessfulCreateCommand(): FrameworkResponse {
        return FrameworkResponse(
            FrameworkResponse.STATUS_CREATED,
            mapOf(
                "errors" to "",
                "code" to FrameworkResponse.STATUS_CREATED.toString(),
                "message" to "",
            ),
        )
    }

    protected fun processSuccessfulCommand(): FrameworkResponse {
        return FrameworkResponse(
            FrameworkResponse.STATUS_OK,
            mapOf(
                "errors" to "",
                "code" to FrameworkResponse.STATUS_OK.toString(),
                "message" to "",
            ),
        )
    }
}
