import {DescriptionEmptyException} from "../../exceptions/DescriptionEmptyException";
import {DescriptionTooLongException} from "../../exceptions/DescriptionTooLongException";
import {Result} from "../../../../common/Result";
import {DomainException} from "../../../../common/domain/DomainException";

export class Description {

    private constructor(
        readonly _value: string,
    ) {}

    public static build(value: string): Result<Description, DomainException> {
        const validation = this.validate(value);
        if (validation.isFailure())
            return Result.failure(validation.getError() as DomainException);

        return Result.success(new Description(value));
    }
    private static validate(value: string): Result<void, DomainException> {
        if (value.length === 0) {
            return Result.failure(DescriptionEmptyException.build());
        }

        if (value.length > 200) {
            return Result.failure(DescriptionTooLongException.withLongitudeMessage(value));
        }

        return Result.success()
    }

    public value(): string {
        return this._value;
    }
}
