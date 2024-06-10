import { AdvertisementRepository } from "../../domain/AdvertisementRepository"
import { UpdateAdvertisementCommand } from "./UpdateAdvertisementCommand"
import {Password} from "../../domain/model/value-object/Password";
import {Description} from "../../domain/model/value-object/Description";
import {AdvertisementId} from "../../domain/model/value-object/AdvertisementId";
import {InvalidPasswordException} from "../exceptions/InvalidPasswordException";
import { Either, left, right } from 'fp-ts/Either';
import {isLeft} from "fp-ts/These";

export class UpdateAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  async execute(command: UpdateAdvertisementCommand): Promise<Either<Error, void>> {
    const advertisementId = AdvertisementId.build(command.id);
    if (isLeft(advertisementId)) {
      return left(advertisementId.left);
    }

    const advertisementResult = await this.advertisementRepository.findById(advertisementId.right);
    if (isLeft(advertisementResult)) {
      return left(advertisementResult.left);
    }

    const advertisement = advertisementResult.right;
    if (!await advertisement.password().isValid(command.password)) {
      return left(InvalidPasswordException.build());
    }

    const newPassword = await Password.fromPlainPassword(command.password);
    if (isLeft(newPassword)) {
      return left(newPassword.left);
    }

    const description = await Description.build(command.description);
    if (isLeft(description)) {
      return left(description.left);
    }

    advertisement.update(
      description.right,
      newPassword.right
    );

    await this.advertisementRepository.save(advertisement);

    return right(undefined);
  }
}
