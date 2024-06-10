import {Password} from "./value-object/Password";
import {Description} from "./value-object/Description";
import {AdvertisementId} from "./value-object/AdvertisementId";
import {AdvertisementDate} from "./value-object/AdvertisementDate";
import {DomainException} from "../../../common/domain/DomainException";
import { Either, left, right } from 'fp-ts/Either';

export class Advertisement {

  private constructor(
    private readonly _id: AdvertisementId,
    private _description: Description,
    private _password: Password,
    private _date: AdvertisementDate
  ) {
  }

  public static build(id: string, description: string, password: Password, date: Date): Either<DomainException, Advertisement> {
    const advertisementIdResult = AdvertisementId.build(id);
    if (advertisementIdResult._tag === 'Left') {
      return left(advertisementIdResult.left);
    }
    const descriptionResult = Description.build(description);
    if (descriptionResult._tag === 'Left') {
      return left(descriptionResult.left);
    }
    const advertisementDateResult = AdvertisementDate.build(date);
    if (advertisementDateResult._tag === 'Left') {
      return left(advertisementDateResult.left);
    }
    return right(new Advertisement(
      advertisementIdResult.right,
      descriptionResult.right,
      password,
      advertisementDateResult.right
    ));
  }

  public update(description: Description, password: Password): Either<DomainException, Advertisement> {
    this._description = description;
    this._password = password;

    const result = this.updateDate();
    if (result._tag === 'Left') {
      return left(result.left);
    }

    return right(this);
  }

  public renew(password: Password): Either<DomainException, Advertisement> {
    this._password = password;

    const result = this.updateDate();
    if (result._tag === 'Left') {
      return left(result.left);
    }

    return right(this);
  }

  public id(): AdvertisementId {
    return this._id
  }

  public description(): Description {
    return this._description
  }

  public password(): Password {
    return this._password
  }

  public date(): AdvertisementDate {
    return this._date
  }

  private updateDate(): Either<DomainException, Advertisement> {
    const advertisementDateResult = AdvertisementDate.build(new Date());
    if (advertisementDateResult._tag === 'Left') {
      return left(advertisementDateResult.left);
    }

    this._date = advertisementDateResult.right;

    return right(this);
  }
}
