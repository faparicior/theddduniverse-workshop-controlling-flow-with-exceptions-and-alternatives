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

export class RenewAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  async execute(command: RenewAdvertisementCommand): Promise<Result<unknown, DomainException>> {
    return AdvertisementId.build(command.id)
      .map(id => id)
      .fold(
        id => this.findAdvertisement(id, command),
        error => Promise.resolve(Result.failure(error as DomainException))
      );
  }

  private async findAdvertisement(id: AdvertisementId, command: RenewAdvertisementCommand): Promise<Result<unknown, DomainException>> {
    const advertisementResult = await this.advertisementRepository.findById(id);
    return advertisementResult
      .map(advertisement => advertisement)
      .fold(
        advertisement => this.validatePasswordMatch(advertisement, command),
        error => {
          if (error instanceof ZeroRecordsException) {
            return Promise.resolve(Result.failure(AdvertisementNotFoundException.withId(id.value())));
          }
          return Promise.resolve(Result.failure(error as DomainException));
        }
      );
  }

  private async validatePasswordMatch(advertisement: Advertisement, command: RenewAdvertisementCommand): Promise<Result<unknown, DomainException>> {
    const passwordResult = await Password.fromPlainPassword(command.password);
    return passwordResult
      .map(password => password)
      .fold(
        password => this.renewAdvertisement(advertisement, password, command),
        error => Promise.resolve(Result.failure(error as DomainException))
      );
  }

  private async renewAdvertisement(advertisement: Advertisement, password: Password, command: RenewAdvertisementCommand): Promise<Result<unknown, DomainException>> {
    if (!await advertisement.password().isValid(command.password)) {
      return Result.failure(InvalidPasswordException.build())
    }

    advertisement.renew(password)

    await this.advertisementRepository.save(advertisement)

    return Result.success()
  }
}
