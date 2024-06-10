import { AdvertisementRepository } from "../../domain/AdvertisementRepository"
import { RenewAdvertisementCommand } from "./RenewAdvertisementCommand"
import {Password} from "../../domain/model/value-object/Password";
import {AdvertisementId} from "../../domain/model/value-object/AdvertisementId";
import {InvalidPasswordException} from "../exceptions/InvalidPasswordException";
import {AdvertisementNotFoundException} from "../../domain/exceptions/AdvertisementNotFoundException";
import {Result} from "../../../common/Result";
import {DomainException} from "../../../common/domain/DomainException";
import {ZeroRecordsException} from "../../infrastructure/exceptions/ZeroRecordsException";
import {Advertisement} from "../../domain/model/Advertisement";
import {chain, Either, left, right} from 'fp-ts/Either';
import { pipe } from 'fp-ts/function';
import {taskEither} from "fp-ts";

export class RenewAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  execute(command: RenewAdvertisementCommand): Promise<Either<DomainException, Advertisement>> {
    return pipe(
      AdvertisementId.build(command.id),
      chain(id => this.findAdvertisement(id)),
      // chain(advertisement => this.validatePassword(advertisement, command.password)),
      // chain(advertisement => this.renewAdvertisement(advertisement, command.password)),
      // chain(advertisement => this.saveAdvertisement(advertisement))
      // AdvertisementId.build(command.id),
      // chain(id => this.findAdvertisement(id)),
      // chain(advertisement => this.validatePassword(advertisement, command.password)),
      // chain(advertisement => this.renewAdvertisement(advertisement, command.password)),
      // chain(advertisement => this.saveAdvertisement(advertisement))
    );

    // return pipe(
    //   AdvertisementId.build(command.id),
    //   chain(id => this.findAdvertisement(id)),
    //   chain(advertisement => this.validatePassword(advertisement, command.password)),
    //   chain(advertisement => this.renewAdvertisement(advertisement, command.password)),
    //   chain(advertisement => this.saveAdvertisement(advertisement))
    // )();
  }

  private async findAdvertisement(id: AdvertisementId): Promise<Either<DomainException, Advertisement>> {
    const advertisement = await this.advertisementRepository.findById(id);
    return advertisement ? right(advertisement) : left(AdvertisementNotFoundException.withId(id.value()));
  }

  private validatePassword(advertisement: Advertisement, password: string): Either<DomainException, Advertisement> {
    return await advertisement.password().isValid(password) ? right(advertisement) : left(InvalidPasswordException.build());
  }

  private renewAdvertisement(advertisement: Advertisement, password: string): Either<DomainException, Advertisement> {
    advertisement.renew(password);
    return right(advertisement);
  }

  private saveAdvertisement(advertisement: Advertisement): Either<DomainException, void> {
    this.advertisementRepository.save(advertisement);
    return right(null);
  }
}
