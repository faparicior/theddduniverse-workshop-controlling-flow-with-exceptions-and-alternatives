import {AdvertisementId} from "../../../../../../src/advertisement/domain/model/value-object/AdvertisementId";
import {
    InvalidUniqueIdentifierException
} from "../../../../../../src/advertisement/domain/exceptions/InvalidUniqueIdentifierException";


describe("Advertisement id", () => {

    const VALID_UUID = "a98e8d18-ebb8-4b34-acb4-8977bfdad42f"
    const INVALID_UUID = "a98e8d18-ebb8-9999-acb4-8977bfdad42f"

    beforeAll(async () => {
    })

    beforeEach(async () => {
    })

    it("Should not be instantiated using the constructor", async () => {
        const functions = Object.getOwnPropertyNames(AdvertisementId);

        expect(functions.includes('constructor')).toBe(false);
    });

    it("Should be created with a valid unique identifier", async () => {
        const result = AdvertisementId.build(VALID_UUID);

        expect(result._tag === 'Right').toBeTruthy();
        expect(result._tag === 'Right' && result.right.value()).toBe(VALID_UUID);
    });

    it("Should throw an exception when has not uuid 4 standards", async () => {
        const result = AdvertisementId.build(INVALID_UUID);

        expect(result._tag === 'Left').toBeTruthy();
        expect(result._tag === 'Left' && result.left).toBeInstanceOf(InvalidUniqueIdentifierException)
    });
});
