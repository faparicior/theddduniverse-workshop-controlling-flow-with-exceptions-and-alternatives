import { AdvertisementRepository } from "../../domain/AdvertisementRepository"
import { UpdateAdvertisementCommand } from "./UpdateAdvertisementCommand"
import {Password} from "../../domain/model/value-object/Password";
import {Description} from "../../domain/model/value-object/Description";
import {AdvertisementId} from "../../domain/model/value-object/AdvertisementId";

export class UpdateAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  async execute(command: UpdateAdvertisementCommand): Promise<void> {
    const advertisementId = new AdvertisementId(command.id)

    const advertisement = await this.advertisementRepository.findById(advertisementId)

    if (!advertisement) {
      throw new ReferenceError('Advertisement not found')
    }

    if (!await advertisement.password().isValid(command.password)) {
      return
    }

    advertisement.update(
        new Description(command.description),
        await Password.fromPlainPassword(command.password)
    )

    await this.advertisementRepository.save(advertisement)
  }
}
