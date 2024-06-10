import {InvalidUniqueIdentifierException} from "../../exceptions/InvalidUniqueIdentifierException";
import {DomainException} from "../../../../common/domain/DomainException";
import { Either, left, right } from 'fp-ts/Either';

export class AdvertisementId {

    private constructor(
        readonly _value: string,
    ) {}

    public static build(value: string): Either<DomainException, AdvertisementId> {
        if (!this.validate(value))
            return left(InvalidUniqueIdentifierException.withId(value));

        return right(new AdvertisementId(value));
    }

    private static validate(value: string): boolean {
        const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[4][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;

        return uuidRegex.test(value)
    }

    public value(): string {
        return this._value;
    }
}
