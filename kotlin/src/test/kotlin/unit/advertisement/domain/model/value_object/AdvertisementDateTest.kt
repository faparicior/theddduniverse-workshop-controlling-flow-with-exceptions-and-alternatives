package unit.advertisement.domain.model.value_object

import advertisement.domain.model.value_object.AdvertisementDate
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.Test
import java.lang.reflect.Modifier
import java.time.LocalDateTime


class AdvertisementDateTest
{
    @Test
    fun testShouldNotBeInstantiatedWithTheConstructor() {
        Assertions.assertThrows(NoSuchMethodException::class.java) {
            Assertions.assertTrue(Modifier.isPrivate(AdvertisementDate::class.java.getDeclaredConstructor().modifiers))
        }
    }

    @Test
    fun testShouldCreateADate() {
        val dateNow = LocalDateTime.now()

        val result = AdvertisementDate.build(dateNow)

        result.fold(
            { error -> Assertions.fail("Expected a valid date, but got error: $error") },
            { id -> Assertions.assertEquals(dateNow, id.value()) }
        )
    }
}
