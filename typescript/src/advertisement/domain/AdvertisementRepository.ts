import { Advertisement } from "./model/Advertisement";
import {AdvertisementId} from "./model/value-object/AdvertisementId";
import {Result} from "../../common/Result";
import {InfrastructureException} from "../../common/infrastructure/InfrastructureException";
import {Either} from "fp-ts/Either";

export interface AdvertisementRepository {

  save(advertisement: Advertisement): Promise<Either<InfrastructureException, void>>;

  findById(id: AdvertisementId): Promise<Either<InfrastructureException, Advertisement>>;
}
