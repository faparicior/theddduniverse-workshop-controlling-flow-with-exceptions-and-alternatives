import {AdvertisementDate} from "../../../../../../src/advertisement/domain/model/value-object/AdvertisementDate";

describe("Advertisement Date", () => {

    beforeAll(async () => {
    })

    beforeEach(async () => {
    })

    it("Should not be instantiated using the constructor", async () => {
        const functions = Object.getOwnPropertyNames(AdvertisementDate);

        expect(functions.includes('constructor')).toBe(false);
    });

    it("Should be created with a Date", async () => {
        const date = new Date();
        const result = AdvertisementDate.build(date);

        expect(result.isSuccess()).toBeTruthy();
        expect(result.getOrThrow().value()).toBe(date);
    });
});
