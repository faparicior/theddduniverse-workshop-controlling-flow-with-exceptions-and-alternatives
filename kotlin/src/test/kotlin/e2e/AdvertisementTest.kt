package e2e

import framework.DependencyInjectionResolver
import framework.FrameworkRequest
import framework.FrameworkResponse
import framework.Server
import framework.database.DatabaseConnection
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.BeforeEach
import org.junit.jupiter.api.Test
import java.security.MessageDigest
import java.time.LocalDateTime


class AdvertisementTest {
    companion object {
        private const val ADVERTISEMENT_CREATION_DATE = "2024-03-04T13:23:15"
        private const val DESCRIPTION = "Dream advertisement"
        private const val NEW_DESCRIPTION = "Dream advertisement changed"
        private const val ID = "6fa00b21-2930-483e-b610-d6b0e5b19b29"
        private const val NON_EXISTENT_ADVERTISEMENT_ID = "99999999-2930-483e-b610-d6b0e5b19b29"
        private const val PASSWORD = "myPassword"
        private const val INCORRECT_PASSWORD = "myBadPassword"

        private const val HTTP_CREATED = "201"
        private const val HTTP_OK = "200"
        private const val HTTP_BAD_REQUEST = "400"
    }

    private lateinit var connection: DatabaseConnection

    @BeforeEach
    fun init() {
        this.connection = DependencyInjectionResolver().connection()
        this.connection.execute("DELETE FROM advertisements")
    }

    @Test
    fun `should publish an advertisement`() {
        val server = Server(DependencyInjectionResolver())

        val result = server.route(FrameworkRequest(
                FrameworkRequest.METHOD_POST,
                "advertisement",
                mapOf(
                    "id" to ID,
                    "description" to DESCRIPTION,
                    "password" to PASSWORD,
                )
            )
        )

        Assertions.assertEquals(FrameworkResponse.STATUS_CREATED, result.statusCode)
        Assertions.assertEquals(successCommandResponse(HTTP_CREATED), result.content)

        val resultSet = this.connection.query("SELECT * from advertisements;")
        var description = ""

        if (resultSet.next()) {
            description = resultSet.getString("description")
        }

        Assertions.assertEquals(DESCRIPTION, description)
    }

    @Test
    fun `should fail publishing an advertisement with same id`() {
        withAnAdvertisementCreated {
            val server = Server(DependencyInjectionResolver())

            val result = server.route(FrameworkRequest(
                FrameworkRequest.METHOD_POST,
                "advertisement",
                mapOf(
                    "id" to ID,
                    "description" to DESCRIPTION,
                    "password" to PASSWORD,
                )
            )
            )

            Assertions.assertEquals(FrameworkResponse.STATUS_BAD_REQUEST, result.statusCode)
            Assertions.assertEquals(
                errorCommandResponse(
                    FrameworkResponse.STATUS_BAD_REQUEST.toString(),
                    "Advertisement with id %s already exists".format(ID)
                ),
                result.content
            )
        }
    }

    @Test
    fun `should update an advertisement`() {
        withAnAdvertisementCreated {
            val server = Server(DependencyInjectionResolver())

            val result = server.route(
                FrameworkRequest(
                    FrameworkRequest.METHOD_PUT,
                    "advertisement/$ID",
                    mapOf(
                        "description" to NEW_DESCRIPTION,
                        "password" to PASSWORD,
                    )
                )
            )

            Assertions.assertEquals(FrameworkResponse.STATUS_OK, result.statusCode)
            Assertions.assertEquals(this.successCommandResponse(HTTP_OK), result.content)

            val resultSet = this.connection.query("SELECT * from advertisements;")
            var description = ""
            var date: LocalDateTime? = null

            if (resultSet.next()) {
                description = resultSet.getString("description")
                date = LocalDateTime.parse(resultSet.getString("advertisement_date"))
            }

            Assertions.assertEquals(NEW_DESCRIPTION, description)
            Assertions.assertNotNull(date)
            Assertions.assertTrue(date!!.isAfter(LocalDateTime.parse(ADVERTISEMENT_CREATION_DATE)))
        }
    }

    @Test
    fun `should renew an advertisement`() {
        withAnAdvertisementCreated {
            val server = Server(DependencyInjectionResolver())

            val result = server.route(
                FrameworkRequest(
                    FrameworkRequest.METHOD_PATCH,
                    "advertisement/$ID",
                    mapOf(
                        "password" to PASSWORD,
                    )
                )
            )

            Assertions.assertEquals(FrameworkResponse.STATUS_OK, result.statusCode)
            Assertions.assertEquals(this.successCommandResponse(HTTP_OK), result.content)

            val resultSet = this.connection.query("SELECT * from advertisements;")
            var date: LocalDateTime? = null

            if (resultSet.next()) {
                date = LocalDateTime.parse(resultSet.getString("advertisement_date"))
            }

            Assertions.assertNotNull(date)
            Assertions.assertTrue(date!!.isAfter(LocalDateTime.parse(ADVERTISEMENT_CREATION_DATE)))
        }
    }

    @Test
    fun `should not update an advertisement with incorrect password`() {

        withAnAdvertisementCreated {
            val server = Server(DependencyInjectionResolver())

            val result = server.route(
                FrameworkRequest(
                    FrameworkRequest.METHOD_PUT,
                    "advertisement/$ID",
                    mapOf(
                        "description" to NEW_DESCRIPTION,
                        "password" to INCORRECT_PASSWORD,
                    )
                )
            )

            Assertions.assertEquals(FrameworkResponse.STATUS_BAD_REQUEST, result.statusCode)
            Assertions.assertEquals(invalidPasswordCommandResponse(), result.content)

            val resultSet = this.connection.query("SELECT * from advertisements;")
            var description = ""
            var date: LocalDateTime? = null

            if (resultSet.next()) {
                description = resultSet.getString("description")
                date = LocalDateTime.parse(resultSet.getString("advertisement_date"))
            }

            Assertions.assertEquals(DESCRIPTION, description)
            Assertions.assertTrue(date!!.isEqual(LocalDateTime.parse(ADVERTISEMENT_CREATION_DATE)))
        }
    }

    @Test
    fun `should not renew an advertisement with incorrect password`() {
        withAnAdvertisementCreated {
            val server = Server(DependencyInjectionResolver())

            val result = server.route(
                FrameworkRequest(
                    FrameworkRequest.METHOD_PATCH,
                    "advertisement/$ID",
                    mapOf(
                        "password" to INCORRECT_PASSWORD,
                    )
                )
            )


            Assertions.assertEquals(FrameworkResponse.STATUS_BAD_REQUEST, result.statusCode)
            Assertions.assertEquals(invalidPasswordCommandResponse(), result.content)
            val resultSet = this.connection.query("SELECT * from advertisements;")
            var date: LocalDateTime? = null

            if (resultSet.next()) {
                date = LocalDateTime.parse(resultSet.getString("advertisement_date"))
            }

            Assertions.assertTrue(date!!.isEqual(LocalDateTime.parse(ADVERTISEMENT_CREATION_DATE)))
        }
    }

    @Test
    fun `should fail renewing non existent advertisement`() {
        val server = Server(DependencyInjectionResolver())

        val result = server.route(FrameworkRequest(
            FrameworkRequest.METHOD_PATCH,
            "advertisement/$NON_EXISTENT_ADVERTISEMENT_ID",
            mapOf(
                "password" to PASSWORD,
            )
        )
        )

        Assertions.assertEquals(FrameworkResponse.STATUS_NOT_FOUND, result.statusCode)
        Assertions.assertEquals(notfoundCommandResponse("Advertisement not found with Id $NON_EXISTENT_ADVERTISEMENT_ID"), result.content)
    }

    @Test
    fun `should fail updating non existent advertisement`() {
        val server = Server(DependencyInjectionResolver())

        val result = server.route(FrameworkRequest(
            FrameworkRequest.METHOD_PUT,
            "advertisement/$NON_EXISTENT_ADVERTISEMENT_ID",
            mapOf(
                "description" to DESCRIPTION,
                "password" to PASSWORD,
            )
        )
        )

        Assertions.assertEquals(FrameworkResponse.STATUS_NOT_FOUND, result.statusCode)
        Assertions.assertEquals(notfoundCommandResponse("Advertisement not found with Id $NON_EXISTENT_ADVERTISEMENT_ID"), result.content)
    }

    private fun successCommandResponse(code: String = "200"): Map<String, String> {
        return mapOf(
            "errors" to "",
            "code" to code,
            "message" to ""
        )
    }

    private fun errorCommandResponse(code: String = "400", message: String): Map<String, String> {
        return mapOf(
            "errors" to message,
            "code" to code,
            "message" to message
        )
    }

    private fun notfoundCommandResponse(message: String): Map<String, String> {
        return mapOf(
            "errors" to message,
            "code" to "404",
            "message" to message
        )
    }

    private fun invalidPasswordCommandResponse(): Map<String, String> {
        return mapOf(
            "errors" to "Password does not match",
            "code" to HTTP_BAD_REQUEST,
            "message" to "Password does not match"
        )
    }

    private fun withAnAdvertisementCreated(block: () -> Unit)
    {
        val password = PASSWORD.md5()
        val creationDate = LocalDateTime.parse(ADVERTISEMENT_CREATION_DATE).toString()
        this.connection.execute("INSERT INTO advertisements (id, description, password, advertisement_date)" +
                " VALUES ('$ID', '$DESCRIPTION', '$password', '$creationDate')")

        block()
    }

    private fun String.md5(): String {
        val md = MessageDigest.getInstance("MD5")
        val digest = md.digest(this.toByteArray())
        val hexString = digest.joinToString("") { "%02x".format(it) }
        return hexString
    }
}
