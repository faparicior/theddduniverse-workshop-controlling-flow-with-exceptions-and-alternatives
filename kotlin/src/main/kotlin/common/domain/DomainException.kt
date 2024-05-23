package common.domain

import common.exceptions.CustomException

open class DomainException(message: String) : CustomException(message)  {
}