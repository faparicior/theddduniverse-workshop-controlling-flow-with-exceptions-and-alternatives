package advertisement.domain.model.value_object

class Description(private var value: String) {

    init {
        this.validate(value)
    }

    fun value(): String {
        return value
    }

    private fun validate(value: String) {
        if (value.isEmpty()) {
            throw IllegalArgumentException("Description is empty")
        }

        if (value.length > 200) {
            throw IllegalArgumentException("Description is too long")
        }
    }
}
