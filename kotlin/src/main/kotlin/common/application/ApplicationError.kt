package common.application

import common.BoundedContextError

open class ApplicationError(message: String) : BoundedContextError(message) {
}
