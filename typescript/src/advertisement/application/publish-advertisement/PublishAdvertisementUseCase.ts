import { AdvertisementRepository } from "../../domain/AdvertisementRepository";
import { Advertisement } from "../../domain/model/Advertisement";
import { PublishAdvertisementCommand } from "./PublishAdvertisementCommand";
import {Password} from "../../domain/model/value-object/Password";
import {AdvertisementId} from "../../domain/model/value-object/AdvertisementId";
import {Description} from "../../domain/model/value-object/Description";
import {AdvertisementDate} from "../../domain/model/value-object/AdvertisementDate";
import {AdvertisementAlreadyExistsException} from "../../domain/exceptions/AdvertisementAlreadyExistsException";

export class PublishAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  async execute(command: PublishAdvertisementCommand): Promise<void> {
    const advertisementId = new AdvertisementId(command.id)

    if(await this.advertisementRepository.findById(advertisementId)) {
      throw AdvertisementAlreadyExistsException.withId(advertisementId.value())
    }

    const advertisement = new Advertisement(
      advertisementId,
      new Description(command.description),
      await Password.fromPlainPassword(command.password),
      new AdvertisementDate(new Date())
    )

    await this.advertisementRepository.save(advertisement)
  }
}
