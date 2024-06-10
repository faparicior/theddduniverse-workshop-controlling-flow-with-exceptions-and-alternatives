import {DescriptionEmptyException} from "../../exceptions/DescriptionEmptyException";
import {DescriptionTooLongException} from "../../exceptions/DescriptionTooLongException";
import {DomainException} from "../../../../common/domain/DomainException";
import { Either, left, right } from 'fp-ts/Either';

export class Description {

    private constructor(
        readonly _value: string,
    ) {}

    public static build(value: string): Either<DomainException, Description> {
        const validation = this.validate(value);
        if (validation._tag === 'Left')
            return left(validation.left);

        return right(new Description(value));
    }

    private static validate(value: string): Either<DomainException, void> {
        if (value.length === 0) {
            return left(DescriptionEmptyException.build());
        }

        if (value.length > 200) {
            return left(DescriptionTooLongException.withLongitudeMessage(value));
        }

        return right(undefined);
    }

    public value(): string {
        return this._value;
    }
}
