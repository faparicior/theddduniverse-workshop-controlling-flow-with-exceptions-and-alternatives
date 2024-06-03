import { Advertisement } from "./model/Advertisement";
import {AdvertisementId} from "./model/value-object/AdvertisementId";

export interface AdvertisementRepository {

  save(name: Advertisement): Promise<void>;

  findById(id: AdvertisementId): Promise<Advertisement | null>;
}
