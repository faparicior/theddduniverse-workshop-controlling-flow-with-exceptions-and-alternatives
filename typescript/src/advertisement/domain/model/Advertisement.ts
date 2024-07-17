import {Password} from "./value-object/Password";
import {Description} from "./value-object/Description";
import {AdvertisementId} from "./value-object/AdvertisementId";
import {AdvertisementDate} from "./value-object/AdvertisementDate";
import {DomainException} from "../../../common/domain/DomainException";
import * as E from '@effect-ts/core/Either';

export class Advertisement {

  private constructor(
    private readonly _id: AdvertisementId,
    private _description: Description,
    private _password: Password,
    private _date: AdvertisementDate
  ) {
  }

  public static build(id: string, description: string, password: Password, date: Date): E.Either<DomainException, Advertisement> {
    const advertisementIdResult = AdvertisementId.build(id);
    if (advertisementIdResult._tag === 'Left') {
      return E.left(advertisementIdResult.left);
    }
    const descriptionResult = Description.build(description);
    if (descriptionResult._tag === 'Left') {
      return E.left(descriptionResult.left);
    }
    const advertisementDateResult = AdvertisementDate.build(date);
    if (advertisementDateResult._tag === 'Left') {
      return E.left(advertisementDateResult.left);
    }
    return E.right(new Advertisement(
      advertisementIdResult.right,
      descriptionResult.right,
      password,
      advertisementDateResult.right
    ));
  }

  public update(description: Description, password: Password): E.Either<DomainException, Advertisement> {
    this._description = description;
    this._password = password;

    const result = this.updateDate();
    if (result._tag === 'Left') {
      return E.left(result.left);
    }

    return E.right(this);
  }

  public renew(password: Password): E.Either<DomainException, Advertisement> {
    this._password = password;

    const result = this.updateDate();
    if (result._tag === 'Left') {
      return E.left(result.left);
    }

    return E.right(this);
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

  private updateDate(): E.Either<DomainException, Advertisement> {
    const advertisementDateResult = AdvertisementDate.build(new Date());
    if (advertisementDateResult._tag === 'Left') {
      return E.left(advertisementDateResult.left);
    }

    this._date = advertisementDateResult.right;

    return E.right(this);
  }
}
