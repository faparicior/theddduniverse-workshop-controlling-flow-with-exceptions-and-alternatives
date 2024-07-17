import * as E from '@effect-ts/core/Either';

export class AdvertisementDate {
    private constructor(
      readonly _value: Date,
    ) {}

    static build(value: Date): E.Either<never, AdvertisementDate> {
        return E.right(new AdvertisementDate(value));
    }

    public value(): Date {
        return this._value;
    }
}