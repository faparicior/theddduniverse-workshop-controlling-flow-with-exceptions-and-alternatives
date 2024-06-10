import { Either, right } from 'fp-ts/Either';

export class AdvertisementDate {

    private constructor(
        readonly _value: Date,
    ) {
    }

    static build(value: Date): Either<never, AdvertisementDate> {
        return right(new AdvertisementDate(value));
    }

    public value(): Date {
        return this._value;
    }
}
