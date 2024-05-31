package common.domain

import common.BoundedContextError

open class DomainError(message: String) : BoundedContextError(message) {
}
