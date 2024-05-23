package common.application

import common.exceptions.CustomException

open class ApplicationException(message: String) : CustomException(message)  {
}