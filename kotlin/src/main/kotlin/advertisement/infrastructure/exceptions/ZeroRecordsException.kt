package advertisement.infrastructure.exceptions

import common.application.ElementNotFoundException

class ZeroRecordsException private constructor(message: String) : ElementNotFoundException(message) {

    companion object {
        fun withId(id: String): ZeroRecordsException {
            return ZeroRecordsException("Advertisement not found with Id $id")
        }
    }
}
