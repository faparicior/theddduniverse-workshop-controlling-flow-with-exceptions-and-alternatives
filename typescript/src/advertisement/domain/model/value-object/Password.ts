import argon2 from "argon2";
import {createHash} from "node:crypto";
import {Result} from "../../../../common/Result";
import {DomainException} from "../../../../common/domain/DomainException";

export class Password {
    private constructor(
        private readonly _password: string,
    ) {
    }

    public static async fromPlainPassword(password: string): Promise<Result<Password, DomainException>> {
        const hash = await argon2.hash(password);

        return Result.success(new Password(hash))
    }

    public static fromEncryptedPassword(password: string): Result<Password, DomainException> {
        return Result.success(new Password(password))
    }

    public async isValid(password: string): Promise<boolean> {
        if (this._password.startsWith('$argon2'))
            return argon2.verify(this._password, password)

        return this._password === createHash('md5').update(password).digest('hex')
    }

    public value(): string {
        return this._password
    }
}
