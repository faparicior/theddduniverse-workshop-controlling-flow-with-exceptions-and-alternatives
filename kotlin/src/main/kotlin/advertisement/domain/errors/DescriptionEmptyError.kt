package advertisement.domain.errors

import common.domain.DomainError

class DescriptionEmptyError private constructor(errorMessage: String): DomainError(errorMessage) {
    companion object {
        fun build(value: String): DescriptionEmptyError {
            return DescriptionEmptyError("Description empty")
        }
    }
}
