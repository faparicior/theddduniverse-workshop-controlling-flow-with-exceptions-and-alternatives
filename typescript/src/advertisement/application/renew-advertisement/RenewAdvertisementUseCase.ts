import { AdvertisementRepository } from "../../domain/AdvertisementRepository"
import { RenewAdvertisementCommand } from "./RenewAdvertisementCommand"
import {Password} from "../../domain/model/value-object/Password";
import {AdvertisementId} from "../../domain/model/value-object/AdvertisementId";
import {InvalidPasswordException} from "../exceptions/InvalidPasswordException";
import {AdvertisementNotFoundException} from "../../domain/exceptions/AdvertisementNotFoundException";
import {Result} from "../../../common/Result";
import {DomainException} from "../../../common/domain/DomainException";
import {ZeroRecordsException} from "../../infrastructure/exceptions/ZeroRecordsException";

export class RenewAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  async execute(command: RenewAdvertisementCommand): Promise<Result<unknown, DomainException>> {
    const advertisementIdResult = AdvertisementId.build(command.id);
    if (advertisementIdResult.isFailure()) {
      return Result.failure(advertisementIdResult.getError() as DomainException);
    }
    const advertisementId = advertisementIdResult.getOrThrow();

    const advertisementResult = await this.advertisementRepository.findById(advertisementId)
    if (advertisementResult.isFailure()) {
      if (advertisementResult.getError() instanceof ZeroRecordsException) {
        return Result.failure(AdvertisementNotFoundException.withId(advertisementId.value())).getOrThrow()
      }
      return Result.failure(advertisementResult.getError() as Error).getOrThrow()
    }
    const advertisement = advertisementResult.getOrThrow()

    const passwordResult = await Password.fromPlainPassword(command.password);
    if (passwordResult.isFailure()) {
      return Result.failure(passwordResult.getError() as DomainException);
    }

    const password = passwordResult.getOrThrow();

    if (!await advertisement.password().isValid(command.password)) {
      return Result.failure(InvalidPasswordException.build())
    }

    advertisement.renew(password)

    await this.advertisementRepository.save(advertisement)

    return Result.success()
  }
}
