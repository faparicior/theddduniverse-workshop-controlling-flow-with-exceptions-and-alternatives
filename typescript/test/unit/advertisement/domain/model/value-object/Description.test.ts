import {Description} from "../../../../../../src/advertisement/domain/model/value-object/Description";
import {
    DescriptionEmptyException
} from "../../../../../../src/advertisement/domain/exceptions/DescriptionEmptyException";
import {
    DescriptionTooLongException
} from "../../../../../../src/advertisement/domain/exceptions/DescriptionTooLongException";


describe("Advertisement description", () => {

    const DESCRIPTION = "my description";

    beforeAll(async () => {
    })

    beforeEach(async () => {
    })

    it("Should not be instantiated using the constructor", async () => {
        const functions = Object.getOwnPropertyNames(Description);

        expect(functions.includes('constructor')).toBe(false);
    });

    it("Should be created with a valid unique description", async () => {
        const result = Description.build(DESCRIPTION);

        expect(result.isSuccess()).toBeTruthy();
        expect(result.getOrThrow().value()).toBe(DESCRIPTION);
    });

    it("Should throw an exception when is empty", async () => {
        const result = Description.build("");

        expect(result.isFailure()).toBeTruthy();
        expect(result.getError()).toBeInstanceOf(DescriptionEmptyException)
    });

    it("Should throw an exception when hs more than 200 characters", async () => {
        const rep = "a".repeat(201);
        const result = Description.build(rep);

        expect(result.isFailure()).toBeTruthy();
        expect(result.getError()).toBeInstanceOf(DescriptionTooLongException)
    });
});
