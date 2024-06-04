
export class AdvertisementId {

    constructor(
        readonly _value: string,
    ) {
        this.validate(_value);
    }

    private validate(value: string) {
        const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[4][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;

        if (!uuidRegex.test(value)) {
            throw new Error("Invalid unique identifier");
        }
    }

    public value(): string {
        return this._value;
    }
}
