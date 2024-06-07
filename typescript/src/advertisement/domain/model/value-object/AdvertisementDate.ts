import {Result} from "../../../../common/Result";

export class AdvertisementDate {

    private constructor(
        readonly _value: Date,
    ) {
    }

    static build(value: Date): Result<AdvertisementDate, any> {
        return Result.success(new AdvertisementDate(value));
    }

    public value(): Date {
        return this._value;
    }
}
