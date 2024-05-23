package unit.advertisement.domain.model.value_object

import advertisement.domain.exceptions.InvalidEmailException
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

        Assertions.assertTrue(result.isSuccess)
        Assertions.assertEquals(VALID_EMAIL, result.getOrNull()!!.value())
    }

    @Test
    fun testShouldThrowAnExceptionWhenEmailIsInvalid() {
        val result = Email.build(INVALID_EMAIL)

        Assertions.assertTrue(result.isFailure)
        Assertions.assertEquals(InvalidEmailException::class.java, result.exceptionOrNull()!!.javaClass)
    }
}
