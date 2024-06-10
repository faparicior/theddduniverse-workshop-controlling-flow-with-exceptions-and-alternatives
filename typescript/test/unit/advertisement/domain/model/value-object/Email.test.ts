import {Email} from "../../../../../../src/advertisement/domain/model/value-object/Email";
import {
    InvalidEmailFormatException
} from "../../../../../../src/advertisement/domain/exceptions/InvalidEmailFormatException";
import {map, getOrElse, getOrElseW} from 'fp-ts/Either';
import {DomainException} from "../../../../../../src/common/domain/DomainException";


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
        const email = map((email: Email) => email.value())(result);

        expect(result._tag === 'Right').toBeTruthy();
        expect(getOrElse(() => 'default')(email)).toBe(EMAIL);
    });

    it("Should throw an exception when email has invalid format", async () => {
        const result = Email.build(INVALID_EMAIL);
        const email = getOrElseW(
          (error: DomainException) => error
        )(result);

        expect(result._tag === 'Left').toBeTruthy();
        expect(email).toBeInstanceOf(InvalidEmailFormatException)
    });
});
