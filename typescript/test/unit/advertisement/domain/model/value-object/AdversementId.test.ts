import {AdvertisementId} from "../../../../../../src/advertisement/domain/model/value-object/AdvertisementId";


describe("Advertisement password", () => {

    const VALID_UUID = "a98e8d18-ebb8-4b34-acb4-8977bfdad42f"

    beforeAll(async () => {
    })

    beforeEach(async () => {
    })

    it("Should be created with a valid unique identifier", async () => {
        let advertisementId = new AdvertisementId(VALID_UUID);

        // expect value
        expect(advertisementId.value()).toBe(VALID_UUID);
    });

    it("Should throw an exception when has not uuid 4 standards", async () => {
        expect(() => {
            new AdvertisementId("invalid");
        }).toThrow("Invalid unique identifier");
    });
});
