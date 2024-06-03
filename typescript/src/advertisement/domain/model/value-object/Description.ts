
export class Description {

    constructor(
        readonly _description: string,
    ) {
        this.validate(_description);
    }

    private validate(_description: string) {
        if () {
            throw new Error("Invalid unique identifier");
        }
    }

    public value(): string {
        return this._description;
    }
}
