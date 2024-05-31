package advertisement.infrastructure.errors

import common.infrastructure.InfrastructureError

class ZeroRecordsError private constructor(val value: String, errorMessage: String) : InfrastructureError(errorMessage) {
    companion object {
        fun withId(value: String): ZeroRecordsError {
            return ZeroRecordsError(value, "Advertisement not found with Id $value")
        }
    }
}
