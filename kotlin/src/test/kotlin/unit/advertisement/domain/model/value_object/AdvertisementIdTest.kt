package unit.advertisement.domain.model.value_object

import advertisement.domain.errors.AdvertisementIdError
import advertisement.domain.model.value_object.AdvertisementId
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.Test
import java.lang.reflect.Modifier


class AdvertisementIdTest
{
    companion object {
        private const val ID = "6fa00b21-2930-483e-b610-d6b0e5b19b29"
        private const val INVALID_ID = "6fa00b21-2930-983e-b610-d6b0e5b19b29"
    }

    @Test
    fun testShouldNotBeInstantiatedWithTheConstructor() {
        Assertions.assertThrows(NoSuchMethodException::class.java) {
            Assertions.assertTrue(Modifier.isPrivate(AdvertisementId::class.java.getDeclaredConstructor().modifiers))
        }
    }

    @Test
    fun testShouldCreateAnAdvertisementId() {
        val result = AdvertisementId.build(ID)

        result.fold(
            { error -> Assertions.fail("Expected a valid id, but got error: $error") },
            { id -> Assertions.assertEquals(ID, id.value()) }
        )
    }

    @Test
    fun testShouldReturnErrorResultIfHasNotUuidV4Standards() {
        val result = AdvertisementId.build(INVALID_ID)

        result.fold(
            { error ->
                Assertions.assertTrue(error is AdvertisementIdError.InvalidFormat)
                Assertions.assertEquals("Invalid unique identifier format for $INVALID_ID", error.errorMessage)
            },
            { id -> Assertions.fail("Expected an error, but got a valid id: ${id.value()}") }
        )
    }
}
