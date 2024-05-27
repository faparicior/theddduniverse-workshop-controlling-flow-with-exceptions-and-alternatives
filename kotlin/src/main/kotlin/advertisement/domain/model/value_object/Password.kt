package advertisement.domain.model.value_object

import advertisement.application.exceptions.InvalidPasswordException
import de.mkammerer.argon2.Argon2Factory
import java.security.MessageDigest

class Password private constructor(private val value: String) {

    companion object {
        fun fromPlainPassword(password: String): Result<Password> {
            val encryptedPassword = Argon2Factory.create().hash(1, 1024, 1, password.toCharArray())

            return Result.success(Password(encryptedPassword))
        }

        fun fromEncryptedPassword(encryptedPassword: String): Result<Password> {
            return Result.success(Password(encryptedPassword))
        }
    }

    fun value(): String {
        return value
    }

    fun isValidatedWith(password: String): Boolean {
        if (value.startsWith("\$argon2i\$")) {
            return Argon2Factory.create().verify(value, password.toCharArray())
        }

        return password.md5() == value
    }

    fun isValidatedWithResult(password: String): Result<Boolean> {
        return try {
            if (value.startsWith("\$argon2i\$")) {
                Result.success(Argon2Factory.create().verify(value, password.toCharArray()))
            } else {
                Result.success(password.md5() == value)
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }

    fun isValidatedWithResultUnit(password: String): Result<Unit> {
        return try {
            if (value.startsWith("\$argon2i\$")) {
                if (Argon2Factory.create().verify(value, password.toCharArray())) return Result.success(Unit)
                Result.failure(InvalidPasswordException.build())
            } else {
                if (password.md5() == value) return Result.success(Unit)
                Result.failure(InvalidPasswordException.build())
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }

    private fun String.md5(): String {
        val md = MessageDigest.getInstance("MD5")
        val digest = md.digest(this.toByteArray())
        val hexString = digest.joinToString("") { "%02x".format(it) }
        return hexString
    }
}
