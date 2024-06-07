import {Password} from "./value-object/Password";
import {Description} from "./value-object/Description";
import {AdvertisementId} from "./value-object/AdvertisementId";
import {AdvertisementDate} from "./value-object/AdvertisementDate";
import {Result} from "../../../common/Result";
import {DomainException} from "../../../common/domain/DomainException";

export class Advertisement {

  private constructor(
    private readonly _id: AdvertisementId,
    private _description: Description,
    private _password: Password,
    private _date: AdvertisementDate
  ) {
  }

  public static build(id: string, description: string, password: Password, date: Date): Result<Advertisement, DomainException> {
    const advertisementIdResult = AdvertisementId.build(id);
    if (advertisementIdResult.isFailure()) {
      return Result.failure(advertisementIdResult.getError() as DomainException);
    }
    const descriptionResult = Description.build(description);
    if (descriptionResult.isFailure()) {
      return Result.failure(descriptionResult.getError() as DomainException);
    }
    const advertisementDateResult = AdvertisementDate.build(date);
    if (advertisementDateResult.isFailure()) {
      return Result.failure(advertisementDateResult.getError() as DomainException);
    }
    return Result.success(new Advertisement(
      advertisementIdResult.getOrThrow(),
      descriptionResult.getOrThrow(),
      password,
      advertisementDateResult.getOrThrow()
    ));
  }

  public update(description: Description, password: Password): Result<Advertisement, DomainException> {
    this._description = description;
    this._password = password;

    const result = this.updateDate();
    if (result.isFailure()) {
      return Result.failure(result.getError() as DomainException);
    }

    return Result.success(this);
  }

  public renew(password: Password): Result<Advertisement, DomainException> {
    this._password = password;

    const result = this.updateDate();
    if (result.isFailure()) {
      return Result.failure(result.getError() as DomainException);
    }

    return Result.success(this);
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

  private updateDate(): Result<Advertisement, DomainException> {
    const advertisementDateResult = AdvertisementDate.build(new Date());
    if (advertisementDateResult.isFailure()) {
      return Result.failure(advertisementDateResult.getError() as DomainException);
    }

    this._date = advertisementDateResult.getOrThrow();

    return Result.success(this);
  }
}
