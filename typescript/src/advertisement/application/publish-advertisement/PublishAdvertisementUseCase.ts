import { AdvertisementRepository } from "../../domain/AdvertisementRepository";
import { Advertisement } from "../../domain/model/Advertisement";
import { PublishAdvertisementCommand } from "./PublishAdvertisementCommand";
import {Password} from "../../domain/model/value-object/Password";
import {AdvertisementId} from "../../domain/model/value-object/AdvertisementId";
import {AdvertisementAlreadyExistsException} from "../../domain/exceptions/AdvertisementAlreadyExistsException";
import {Result} from "../../../common/Result";
import {DomainException} from "../../../common/domain/DomainException";

export class PublishAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  async execute(command: PublishAdvertisementCommand): Promise<Result<void, DomainException>> {
    await this.assureAdvertisementDoesNotExist(command)

    const passwordResult = await Password.fromPlainPassword(command.password);
    if (passwordResult.isFailure()) {
      return Result.failure(passwordResult.getError() as DomainException);
    }

    const advertisementResult = Advertisement.build(
      command.id,
      command.description,
      passwordResult.getOrThrow(),
      new Date()
    )

    if (advertisementResult.isFailure()) {
      return Result.failure(advertisementResult.getError() as DomainException);
    }

    await this.advertisementRepository.save(advertisementResult.getOrThrow())

    return Result.success();
  }

  private async assureAdvertisementDoesNotExist(command: PublishAdvertisementCommand): Promise<Result<void, DomainException>> {
    const advertisementIdResult = AdvertisementId.build(command.id);
    if (advertisementIdResult.isFailure()) {
      return Result.failure(advertisementIdResult.getError() as DomainException);
    }
    const advertisementId = advertisementIdResult.getOrThrow();
    if (await this.advertisementRepository.findById(advertisementId)) {
      return Result.failure(AdvertisementAlreadyExistsException.withId(advertisementId.value()));
    }
    return Result.success();
  }
}
