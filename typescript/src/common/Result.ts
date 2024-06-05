export class Result<T, E extends Error> {
    private readonly type: ResultType;
    private readonly value?: T;
    private readonly error?: E;

    constructor(type: ResultType, value?: T, error?: E) {
        this.type = type;
        if (type === ResultType.Success && error !== undefined) {
            throw new Error("Result cannot be both successful and have an error");
        }
        if (type === ResultType.Failure && value !== undefined) {
            throw new Error("Result cannot be both failed and have a value");
        }
        this.value = value;
        this.error = error;
    }

    public isSuccess(): boolean {
        return this.type === ResultType.Success;
    }

    public isFailure(): boolean {
        return this.type === ResultType.Failure;
    }

    public getOrThrow(): T {
        if (this.isSuccess()) {
            return this.value!; // Safe to use ! because of type guard
        }
        throw this.error!; // Re-throw the encapsulated error
    }

    public getError(): E | undefined {
        if (this.isFailure()) {
            return this.error;
        }
        return undefined;
    }

    public static success<T>(value?: T): Result<T, any> {
        return new Result(ResultType.Success, value);
    }

    public static failure<E extends Error>(error: E): Result<any, E> {
        return new Result(ResultType.Failure, undefined, error);
    }
}

enum ResultType {
    Success,
    Failure,
}