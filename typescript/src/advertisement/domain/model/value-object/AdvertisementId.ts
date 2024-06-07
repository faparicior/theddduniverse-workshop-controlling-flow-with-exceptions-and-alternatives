import {InvalidUniqueIdentifierException} from "../../exceptions/InvalidUniqueIdentifierException";
import {Result} from "../../../../common/Result";
import {DomainException} from "../../../../common/domain/DomainException";

export class AdvertisementId {

    private constructor(
        readonly _value: string,
    ) {}

    public static build(value: string): Result<AdvertisementId, DomainException> {
        if (!this.validate(value))
            return Result.failure(InvalidUniqueIdentifierException.withId(value));

        return Result.success(new AdvertisementId(value));
    }

    private static validate(value: string): boolean {
        const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[4][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;

        return uuidRegex.test(value)
    }

    public value(): string {
        return this._value;
    }
}
