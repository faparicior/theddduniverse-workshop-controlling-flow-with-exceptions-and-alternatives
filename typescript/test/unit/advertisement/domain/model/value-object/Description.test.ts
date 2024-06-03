import {AdvertisementId} from "../../../../../../src/advertisement/domain/model/value-object/AdvertisementId";


describe("Advertisement password", () => {

    beforeAll(async () => {
    })

    beforeEach(async () => {
    })

    it("Should be created with a valid unique description", async () => {
        let advertisementId = new Description();

        // expect value
        expect(advertisementId.value()).toBe(VALID_UUID);
    });

    it("Should throw an exception when has not uuid 4 standards", async () => {
        expect(() => {
            new AdvertisementId("invalid");
        }).toThrow("Invalid unique identifier");
    });
});
