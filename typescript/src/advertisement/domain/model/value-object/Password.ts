import argon2 from "argon2";
import {createHash} from "node:crypto";
import * as E from '@effect-ts/core/Either';
import {DomainException} from "../../../../common/domain/DomainException";

export class Password {
    private constructor(
        private readonly _password: string,
    ) {
    }

    public static async fromPlainPassword(password: string): Promise<E.Either<DomainException, Password>> {
        try {
            const hash = await argon2.hash(password);
            return E.right(new Password(hash));
        } catch (error) {
            return E.left(new DomainException('Error hashing password'));
        }
    }

    public static fromEncryptedPassword(password: string): E.Either<DomainException, Password> {
        return E.right(new Password(password));
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
