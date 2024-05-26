package advertisement.domain.exceptions

class DescriptionEmptyException private constructor(message: String) : Exception(message) {

    companion object {
        fun build(): DescriptionEmptyException {
            return DescriptionEmptyException("Description empty")
        }
    }

    override fun fillInStackTrace(): Throwable {
        if (shouldIncludeStackTrace()) {
            return super.fillInStackTrace()
        }
        return this
    }

    private fun shouldIncludeStackTrace(): Boolean {
        // Implement logic to determine if stack trace is needed (e.g., based on configuration)
        return false
    }
}
