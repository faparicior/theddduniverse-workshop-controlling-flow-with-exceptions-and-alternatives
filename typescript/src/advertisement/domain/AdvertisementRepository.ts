import { Advertisement } from "./model/Advertisement";
import {AdvertisementId} from "./model/value-object/AdvertisementId";
import {InfrastructureException} from "../../common/infrastructure/InfrastructureException";
import * as E from "@effect-ts/core/Either";

export interface AdvertisementRepository {

  save(advertisement: Advertisement): Promise<E.Either<InfrastructureException, void>>;

  findById(id: AdvertisementId): Promise<E.Either<InfrastructureException, Advertisement>>;
}
