import {AdvertisementDate} from "../../../../../../src/advertisement/domain/model/value-object/AdvertisementDate";

describe("Advertisement Date", () => {

    const VALID_DATE = new Date();

    beforeAll(async () => {
    })

    beforeEach(async () => {
    })

    it("Should not be instantiated using the constructor", async () => {
        const functions = Object.getOwnPropertyNames(AdvertisementDate);

        expect(functions.includes('constructor')).toBe(false);
    });

    it("Should be created with a valid date", async () => {
        const result = AdvertisementDate.build(VALID_DATE);

        expect(result._tag === 'Right').toBeTruthy();
        expect(result._tag === 'Right' && result.right.value()).toEqual(VALID_DATE);
    });
});
