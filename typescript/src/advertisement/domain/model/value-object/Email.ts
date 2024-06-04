import {sprintf} from "sprintf-js";

export class Email {
    private static readonly EMAIL_REGEX = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

    constructor(
        readonly _value: string,
    ) {
        this.validate(_value);
    }

    private validate(value: string) {
        if (!Email.EMAIL_REGEX.test(value)) {
            throw new Error(sprintf("Invalid email format: %s", value));
        }
    }

    public value(): string {
        return this._value;
    }
}
