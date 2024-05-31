package common.ui.http

import arrow.core.Either
import common.BoundedContextError
import common.domain.EntityNotFoundDomainError
import framework.FrameworkResponse

abstract class CommonController {

    protected fun processGenericException(exception: Throwable): FrameworkResponse {
        return FrameworkResponse(
            FrameworkResponse.STATUS_INTERNAL_SERVER_ERROR,
            mapOf(
                "errors" to exception.message.toString(),
                "code" to FrameworkResponse.STATUS_BAD_REQUEST.toString(),
                "message" to exception.message.toString(),
            ),
        )
    }

    protected fun processError(error: BoundedContextError): FrameworkResponse {
        return FrameworkResponse(
            FrameworkResponse.STATUS_BAD_REQUEST,
            mapOf(
                "errors" to error.message,
                "code" to FrameworkResponse.STATUS_BAD_REQUEST.toString(),
                "message" to error.message,
            ),
        )
    }

    protected fun processSuccessfulCommand(statusCode: Int): FrameworkResponse {
        return FrameworkResponse(
            statusCode,
            mapOf(
                "errors" to "",
                "code" to statusCode.toString(),
                "message" to "",
            ),
        )
    }

    protected fun processNotFoundCommand(error: EntityNotFoundDomainError): FrameworkResponse {
        val message = error.message

        return FrameworkResponse(
            FrameworkResponse.STATUS_NOT_FOUND,
            mapOf(
                "errors" to message,
                "code" to FrameworkResponse.STATUS_NOT_FOUND.toString(),
                "message" to message,
            ),
        )
    }

    protected fun processResult(result: Either<BoundedContextError, Any>, okStatusCode: Int = FrameworkResponse.STATUS_OK): FrameworkResponse {
        return when (result) {
            is Either.Left -> processTypeOfError(result.value)
            is Either.Right -> processSuccessfulCommand(okStatusCode)
        }
    }

    private fun processTypeOfError(error: BoundedContextError): FrameworkResponse {
        return when (error) {
            is EntityNotFoundDomainError -> processNotFoundCommand(error)
            else -> processError(error)
        }
    }
}