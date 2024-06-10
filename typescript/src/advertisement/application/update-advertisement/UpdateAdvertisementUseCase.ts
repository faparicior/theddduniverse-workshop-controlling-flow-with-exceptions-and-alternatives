import { AdvertisementRepository } from "../../domain/AdvertisementRepository"
import { UpdateAdvertisementCommand } from "./UpdateAdvertisementCommand"
import {Password} from "../../domain/model/value-object/Password";
import {Description} from "../../domain/model/value-object/Description";
import {AdvertisementId} from "../../domain/model/value-object/AdvertisementId";
import {InvalidPasswordException} from "../exceptions/InvalidPasswordException";
import {Result} from "../../../common/Result";
import {ZeroRecordsException} from "../../infrastructure/exceptions/ZeroRecordsException";
import {AdvertisementNotFoundException} from "../../domain/exceptions/AdvertisementNotFoundException";

export class UpdateAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  async execute(command: UpdateAdvertisementCommand): Promise<Result<unknown, Error>> {
    return await Result.runCatching(async () => {
      const advertisementId = AdvertisementId.build(command.id).getOrThrow()
      const advertisementResult = (await this.advertisementRepository.findById(advertisementId))

      if (advertisementResult.isFailure()) {
        if (advertisementResult.getError() instanceof ZeroRecordsException) {
          return Result.failure(AdvertisementNotFoundException.withId(advertisementId.value())).getOrThrow()
        }
        return Result.failure(advertisementResult.getError() as Error).getOrThrow()
      }

      const advertisement = advertisementResult.getOrThrow()

      if (!await advertisement.password().isValid(command.password)) {
        return Result.failure(InvalidPasswordException.build()).getOrThrow()
      }

      advertisement.update(
          Description.build(command.description).getOrThrow(),
          (await Password.fromPlainPassword(command.password)).getOrThrow()
      );

      await this.advertisementRepository.save(advertisement)

      return Result.success().getOrThrow()
    })
  }
}
