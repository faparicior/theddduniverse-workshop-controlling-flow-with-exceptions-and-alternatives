package unit.advertisement.domain.model.value_object

import advertisement.domain.errors.DescriptionEmptyError
import advertisement.domain.errors.DescriptionTooLongError
import advertisement.domain.model.value_object.Description
import arrow.core.*
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.Test
import java.lang.reflect.Modifier


class DescriptionTest
{
    companion object {
        private const val VALID_DESCRIPTION = "Description test"
        private const val EMPTY_DESCRIPTION = ""
        private val LONG_DESCRIPTION = "a".repeat(201)
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

        result.fold(
            { error -> Assertions.fail("Expected a valid description, but got error: $error") },
            { description -> Assertions.assertEquals(VALID_DESCRIPTION, description.value()) }
        )
    }

    @Test
    fun testShouldReturnErrorForEmptyDescription() {
        val result = Description.build(EMPTY_DESCRIPTION)

        Assertions.assertTrue(result is Either.Left)
        result.fold(
            { error ->
                Assertions.assertTrue(error is DescriptionEmptyError)
                Assertions.assertEquals("Description empty", error.message)
            },
            { description -> Assertions.fail("Expected an error, but got a valid description: ${description.value()}") }
        )
    }

    @Test
    fun testShouldReturnErrorForTooLongDescription() {
        val result = Description.build(LONG_DESCRIPTION)

        Assertions.assertTrue(result is Either.Left)
        result.fold(
            { error ->
                Assertions.assertTrue(error is DescriptionTooLongError)
                Assertions.assertEquals("Description has more than 200 characters: Has ${LONG_DESCRIPTION.length}", error.message)
            },
            { description -> Assertions.fail("Expected an error, but got a valid description: ${description.value()}") }
        )
    }
}
