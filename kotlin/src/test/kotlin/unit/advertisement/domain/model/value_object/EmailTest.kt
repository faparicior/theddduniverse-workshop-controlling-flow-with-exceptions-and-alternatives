package unit.advertisement.domain.model.value_object

import advertisement.domain.errors.EmailError
import advertisement.domain.model.value_object.Email
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.Test
import java.lang.reflect.Modifier

class EmailTest
{
    companion object {
        private const val VALID_EMAIL = "email@test.con"
        private const val INVALID_EMAIL = "email.test.con"
    }

    @Test
    fun testShouldNotBeInstantiatedWithTheConstructor() {
        Assertions.assertThrows(NoSuchMethodException::class.java) {
            Assertions.assertTrue(Modifier.isPrivate(Email::class.java.getDeclaredConstructor().modifiers))
        }
    }

    @Test
    fun testShouldCreateAnEmail() {
        val result = Email.build(VALID_EMAIL)

        result.fold(
            { error -> Assertions.fail("Expected a valid email, but got error: $error") },
            { id -> Assertions.assertEquals(VALID_EMAIL, id.value()) }
        )
    }

    @Test
    fun testShouldThrowAnExceptionWhenEmailIsInvalid() {
        val result = Email.build(INVALID_EMAIL)

        result.fold(
            { error ->
                Assertions.assertTrue(error is EmailError.InvalidEmailFormat)
                Assertions.assertEquals("Invalid email format $INVALID_EMAIL", error.errorMessage)
            },
            { description -> Assertions.fail("Expected an error, but got a valid email: ${description.value()}") }
        )
    }
}
