import {InvalidEmailFormatException} from "../../exceptions/InvalidEmailFormatException";
import {Result} from "../../../../common/Result";
import {DomainException} from "../../../../common/domain/DomainException";
import { Either, left, right } from 'fp-ts/Either';

export class Email {
    private static readonly EMAIL_REGEX = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

    private constructor(
        readonly _value: string,
    ) {}

    public static build(value: string): Either<DomainException, Email> {
        if (!this.validate(value)) {
            return left(InvalidEmailFormatException.withEmail(value));
        }
        return right(new Email(value));
    }
    private static validate(value: string): boolean {
        return Email.EMAIL_REGEX.test(value)
    }

    public value(): string {
        return this._value;
    }
}
