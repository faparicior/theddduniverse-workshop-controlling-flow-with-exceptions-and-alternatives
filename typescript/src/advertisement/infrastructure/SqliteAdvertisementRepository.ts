import { AdvertisementRepository } from '../domain/AdvertisementRepository';
import { Advertisement } from '../domain/model/Advertisement';
import { DatabaseConnection } from '../../framework/database/DatabaseConnection';
import {Password} from "../domain/model/value-object/Password";
import {AdvertisementId} from "../domain/model/value-object/AdvertisementId";
import {Result} from "../../common/Result";
import {ZeroRecordsException} from "./exceptions/ZeroRecordsException";
import {InfrastructureException} from "../../common/infrastructure/InfrastructureException";

export class SqliteAdvertisementRepository implements AdvertisementRepository {

  constructor(
    private connection: DatabaseConnection) {
  }

  async findById(id: AdvertisementId): Promise<Result<Advertisement, InfrastructureException>> {

    const result = await this.connection.query(`SELECT * FROM advertisements WHERE id = ? `, [id.value()])

    if (!result || result.length < 1) {
      return  Result.failure(ZeroRecordsException.build())
    }

    const row = result[0] as any;

    const passwordResult = Password.fromEncryptedPassword(row.password);
    if (passwordResult.isFailure()) {
      return Result.failure(passwordResult.getError() as any);
    }

    return Advertisement.build(
      row.id,
      row.description,
      passwordResult.getOrThrow(),
      new Date(row.advertisement_date)
    )
  }

  async save(advertisement: Advertisement): Promise<Result<void, InfrastructureException>> {

    await this.connection.execute(
      `INSERT INTO advertisements (id, description, password, advertisement_date) 
      VALUES (?, ?, ?, ?) 
      ON CONFLICT(id) DO UPDATE 
      SET description = excluded.description, password = excluded.password, advertisement_date = excluded.advertisement_date`, [
      advertisement.id().value(),
      advertisement.description().value(),
      advertisement.password().value(),
      advertisement.date().value().toISOString(),
    ]);

    return Result.success();
  }
}
