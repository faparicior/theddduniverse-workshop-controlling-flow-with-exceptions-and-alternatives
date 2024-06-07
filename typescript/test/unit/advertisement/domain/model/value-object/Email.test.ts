import {Email} from "../../../../../../src/advertisement/domain/model/value-object/Email";
import {
    InvalidEmailFormatException
} from "../../../../../../src/advertisement/domain/exceptions/InvalidEmailFormatException";


describe("Advertisement email", () => {

    const EMAIL = "test@test.com";
    const INVALID_EMAIL = "test.test.com";

    beforeAll(async () => {
    })

    beforeEach(async () => {
    })

    it("Should not be instantiated using the constructor", async () => {
        const functions = Object.getOwnPropertyNames(Email);

        expect(functions.includes('constructor')).toBe(false);
    });

    it("Should be created with a valid email", async () => {
        const result = Email.build(EMAIL);

        expect(result.isSuccess).toBeTruthy();
        expect(result.getOrThrow().value()).toBe(EMAIL);
    });

    it("Should throw an exception when email has invalid format", async () => {
        const result = Email.build(INVALID_EMAIL);

        expect(result.isFailure()).toBeTruthy();
        expect(result.getError()).toBeInstanceOf(InvalidEmailFormatException)
    });
});
