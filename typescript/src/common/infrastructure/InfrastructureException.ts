import {BoundedContextException} from "../exceptions/BoundedContextException";

export class InfrastructureException extends BoundedContextException {
  constructor(message?: string) {
    super(message);
    this.name = 'InfrastructureException';
    Object.setPrototypeOf(this, new.target.prototype);
  }
}
