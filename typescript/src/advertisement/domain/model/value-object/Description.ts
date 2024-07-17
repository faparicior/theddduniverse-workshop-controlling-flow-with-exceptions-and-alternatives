import { DescriptionEmptyException } from "../../exceptions/DescriptionEmptyException";
import { DescriptionTooLongException } from "../../exceptions/DescriptionTooLongException";
import { DomainException } from "../../../../common/domain/DomainException";
import * as E from '@effect-ts/core/Either';

export class Description {

    private constructor(
      readonly _value: string,
    ) {}

    public static build(value: string): E.Either<DomainException, Description> {
        const validation = this.validate(value);
        if (E.isLeft(validation))
            return E.left(validation.left);

        return E.right(new Description(value));
    }

    private static validate(value: string): E.Either<DomainException, void> {
        if (value.length === 0) {
            return E.left(DescriptionEmptyException.build());
        }

        if (value.length > 200) {
            return E.left(DescriptionTooLongException.withLongitudeMessage(value));
        }

        return E.right(undefined);
    }

    public value(): string {
        return this._value;
    }
}