import {InfrastructureException} from "../../../common/infrastructure/InfrastructureException";

export class ZeroRecordsException extends InfrastructureException {

  private static _message: string = 'Invalid password';

  private constructor(message?: string) {
    super(message);
    this.name = 'ZeroRecordsException';
    Object.setPrototypeOf(this, new.target.prototype);
  }

  static build(message: string = ZeroRecordsException._message): ZeroRecordsException {
    return new ZeroRecordsException(message);
  }
}
