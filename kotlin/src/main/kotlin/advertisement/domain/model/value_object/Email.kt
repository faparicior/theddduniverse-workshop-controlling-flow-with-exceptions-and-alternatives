package advertisement.domain.model.value_object

class Email(private var value: String) {

    init {
        if (!this.validate(value)) {
            throw IllegalArgumentException("Invalid email")
        }
    }

    fun value(): String {
        return value
    }

    private fun validate(value: String): Boolean {
        val regex = "^[\\w-\\.]+@([\\w-]+\\.)+[\\w-]{2,4}\$"
        return value.matches(regex.toRegex())
    }
}
