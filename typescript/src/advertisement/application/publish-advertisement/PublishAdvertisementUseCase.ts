import { AdvertisementRepository } from "../../domain/AdvertisementRepository";
import { Advertisement } from "../../domain/model/Advertisement";
import { PublishAdvertisementCommand } from "./PublishAdvertisementCommand";
import {Password} from "../../domain/model/value-object/Password";
import {AdvertisementId} from "../../domain/model/value-object/AdvertisementId";
import {AdvertisementAlreadyExistsException} from "../../domain/exceptions/AdvertisementAlreadyExistsException";
import {DomainException} from "../../../common/domain/DomainException";
import { Either, left, right, map, chain } from 'fp-ts/Either';
import { pipe } from 'fp-ts/lib/function';
import {InvalidPasswordException} from "../exceptions/InvalidPasswordException";
import {InfrastructureException} from "../../../common/infrastructure/InfrastructureException";

export class PublishAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  execute(command: PublishAdvertisementCommand): Promise<Either<DomainException, void>> {
    return pipe(
      AdvertisementId.build(command.id),
      chain(id => this.findAdvertisement(id)),
      chain(advertisement => this.validatePassword(advertisement, command.password)),
      chain(advertisement => this.publishAdvertisement(advertisement, command.password)),
      chain(advertisement => this.saveAdvertisement(advertisement)),
      map(() => undefined)
    )();
  }

  private findAdvertisement(id: AdvertisementId): Either<never, Promise<Either<InfrastructureException, Advertisement>>> | Either<AdvertisementAlreadyExistsException, never> {
    const advertisement = this.advertisementRepository.findById(id);
    return advertisement ? right(advertisement) : left(AdvertisementAlreadyExistsException.withId(id.value()));
  }

  private async validatePassword(advertisement: Advertisement, password: string): Promise<Either<DomainException, Advertisement>> {
    return await advertisement.password().isValid(password) ? right(advertisement) : left(InvalidPasswordException.build());
  }

  private publishAdvertisement(advertisement: Advertisement, password: string): Either<DomainException, Advertisement> {
    advertisement.publish(password);
    return right(advertisement);
  }

  private saveAdvertisement(advertisement: Advertisement): Either<DomainException, void> {
    this.advertisementRepository.save(advertisement);
    return right(undefined);
  }
}
