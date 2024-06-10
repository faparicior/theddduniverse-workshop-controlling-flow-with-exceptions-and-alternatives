import {Description} from "../../../../../../src/advertisement/domain/model/value-object/Description";
import {
    DescriptionEmptyException
} from "../../../../../../src/advertisement/domain/exceptions/DescriptionEmptyException";
import {
    DescriptionTooLongException
} from "../../../../../../src/advertisement/domain/exceptions/DescriptionTooLongException";
import {getOrElse} from "fp-ts/Either";
import {DomainException} from "../../../../../../src/common/domain/DomainException";
import { map, getOrElseW } from 'fp-ts/Either';


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
        const description = map((description: Description) => description.value())(result);

        expect(result._tag === 'Right').toBeTruthy();
        expect(getOrElse(() => 'default')(description)).toBe(DESCRIPTION);
    });

    it("Should throw an exception when is empty", async () => {
        const result = Description.build("");
        const description = getOrElseW(
          (error: DomainException) => error
        )(result);

        expect(result._tag === 'Left').toBeTruthy();
        expect(description).toBeInstanceOf(DescriptionEmptyException)
    });

    it("Should throw an exception when hs more than 200 characters", async () => {
        const rep = "a".repeat(201);
        const result = Description.build(rep);
        const description = getOrElseW(
          (error: DomainException) => error
        )(result);

        expect(result._tag === 'Left').toBeTruthy();
        expect(description).toBeInstanceOf(DescriptionTooLongException)
    });
});
