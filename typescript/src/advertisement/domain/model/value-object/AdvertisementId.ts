
export class AdvertisementId {

    constructor(
        readonly _advertisementId: string,
    ) {
        this.validate(_advertisementId);
    }

    private validate(advertisementId: string) {
        const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[4][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;

        if (!uuidRegex.test(advertisementId)) {
            throw new Error("Invalid unique identifier");
        }
    }

    public value(): string {
        return this._advertisementId;
    }
}
