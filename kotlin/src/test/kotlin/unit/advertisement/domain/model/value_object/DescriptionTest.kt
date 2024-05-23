package unit.advertisement.domain.model.value_object

import advertisement.domain.exceptions.DescriptionEmptyException
import advertisement.domain.exceptions.DescriptionTooLongException
import advertisement.domain.model.value_object.Description
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.Test
import java.lang.reflect.Modifier


class DescriptionTest
{
    companion object {
        private const val VALID_DESCRIPTION = "Description test"
    }

    @Test
    fun testShouldNotBeInstantiatedWithTheConstructor() {
        Assertions.assertThrows(NoSuchMethodException::class.java) {
            Assertions.assertTrue(Modifier.isPrivate(Description::class.java.getDeclaredConstructor().modifiers))
        }
    }

    @Test
    fun testShouldCreateADescription() {
        val result = Description.build(VALID_DESCRIPTION)

        Assertions.assertTrue(result.isSuccess)
        Assertions.assertEquals(VALID_DESCRIPTION, result.getOrNull()!!.value())
    }

    @Test
    fun testShouldReturnErrorResultWhenDescriptionIsEmpty() {
        val result = Description.build("")

        Assertions.assertTrue(result.isFailure)
        Assertions.assertEquals(DescriptionEmptyException::class.java, result.exceptionOrNull()!!.javaClass)
    }

    @Test
    fun testShouldReturnErrorResultWhenDescriptionIsTooLong() {
        val longDescription = "a".repeat(201)
        val result = Description.build(longDescription)

        Assertions.assertTrue(result.isFailure)
        Assertions.assertEquals(DescriptionTooLongException::class.java, result.exceptionOrNull()!!.javaClass)
    }
}
