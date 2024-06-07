import { Advertisement } from "./model/Advertisement";
import {AdvertisementId} from "./model/value-object/AdvertisementId";
import {Result} from "../../common/Result";
import {InfrastructureException} from "../../common/infrastructure/InfrastructureException";

export interface AdvertisementRepository {

  save(name: Advertisement): Promise<Result<void, InfrastructureException>>;

  findById(id: AdvertisementId): Promise<Result<Advertisement, InfrastructureException>>;
}
