package unit.advertisement.domain.model.value_object

import advertisement.domain.model.value_object.Password
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.Test
import java.lang.reflect.Modifier


class PasswordTest
{
    companion object {
        private const val STRONG_ALGORITHM_PASSWORD = "\$argon2i\$v=19\$m=65536,t=4,p=1\$b0t4M3dsYTlzNXlEaFBJTQ$1qxVJUQMvov1pe8zDpB03bTRYARs08X8GPhEvHKXVAI"
        private const val MD5_ALGORITHM_PASSWORD = "deb1536f480475f7d593219aa1afd74c"
    }

    @Test
    fun testShouldNotBeInstantiatedWithTheConstructor() {
        Assertions.assertThrows(NoSuchMethodException::class.java) {
            Assertions.assertTrue(Modifier.isPrivate(Password::class.java.getDeclaredConstructor().modifiers))
        }
    }

    @Test
    fun testShouldBeCreatedWithAStrongHash() {
        val result = Password.fromPlainPassword("password")

        result.fold(
            { error -> Assertions.fail("Expected a valid id, but got error: $error") },
            { password -> Assertions.assertTrue(password.value().startsWith("\$argon2i\$")) }
        )
    }

    @Test
    fun testShouldBeCreatedWithEncryptedValueWithoutChangeTheOriginalHash() {
        val strongPasswordResult = Password.fromEncryptedPassword(STRONG_ALGORITHM_PASSWORD)
        val weakPasswordResult = Password.fromEncryptedPassword(MD5_ALGORITHM_PASSWORD)

        Assertions.assertEquals(STRONG_ALGORITHM_PASSWORD, strongPasswordResult.getOrNull()!!.value())
        Assertions.assertEquals(MD5_ALGORITHM_PASSWORD, weakPasswordResult.getOrNull()!!.value())
    }

    @Test
    fun testShouldValidatePasswordsWithAStrongAlgorithm() {
        val result = Password.fromEncryptedPassword(STRONG_ALGORITHM_PASSWORD)

        Assertions.assertTrue(result.getOrNull()!!.isValidatedWith("myPassword"))
    }

    @Test
    fun testShouldValidatePasswordsWithAWeakAlgorithm() {
        val result = Password.fromEncryptedPassword(MD5_ALGORITHM_PASSWORD)

        Assertions.assertTrue(result.getOrNull()!!.isValidatedWith("myPassword"))
    }
}
