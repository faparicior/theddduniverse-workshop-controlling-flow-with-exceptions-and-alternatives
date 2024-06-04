import {sprintf} from "sprintf-js";

export class Description {

    constructor(
        readonly _value: string,
    ) {
        this.validate(_value);
    }

    private validate(value: string) {
        if (value.length === 0) {
            throw new Error("Description empty");
        }

        if (value.length > 200) {
            throw new Error(sprintf("Description has more than 200 characters: Has %d characters", this._value.length));
        }
    }

    public value(): string {
        return this._value;
    }
}
