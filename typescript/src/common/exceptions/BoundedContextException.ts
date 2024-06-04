export class BoundedContextException extends Error {
  constructor(message?: string) {
    super(message);
    this.name = 'BoundedContextException';
    Object.setPrototypeOf(this, new.target.prototype);
  }
}
