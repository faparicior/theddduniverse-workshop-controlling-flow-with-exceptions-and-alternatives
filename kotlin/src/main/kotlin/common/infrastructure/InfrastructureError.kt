package common.infrastructure

import common.BoundedContextError

open class InfrastructureError(message: String) : BoundedContextError(message) {
}
