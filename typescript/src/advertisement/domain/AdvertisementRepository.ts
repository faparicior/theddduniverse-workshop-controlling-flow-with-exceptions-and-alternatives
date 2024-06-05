import { Advertisement } from "./model/Advertisement";
import {AdvertisementId} from "./model/value-object/AdvertisementId";
import {Result} from "../../common/Result";

export interface AdvertisementRepository {

  save(name: Advertisement): Promise<Result<void, any>>;

  findById(id: AdvertisementId): Promise<Advertisement | null>;
}
