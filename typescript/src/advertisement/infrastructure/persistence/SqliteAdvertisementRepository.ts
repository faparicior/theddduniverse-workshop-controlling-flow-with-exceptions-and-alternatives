import { AdvertisementRepository } from '../../domain/AdvertisementRepository';
import { Advertisement } from '../../domain/model/Advertisement';
import { DatabaseConnection } from '../../../framework/database/DatabaseConnection';
import {Password} from "../../domain/model/value-object/Password";
import {AdvertisementId} from "../../domain/model/value-object/AdvertisementId";
import {ZeroRecordsException} from "../exceptions/ZeroRecordsException";
import {InfrastructureException} from "../../../common/infrastructure/InfrastructureException";
import { Either, left, right } from 'fp-ts/Either';

export class SqliteAdvertisementRepository implements AdvertisementRepository {

  constructor(
    private connection: DatabaseConnection) {
  }

  async findById(id: AdvertisementId): Promise<Either<InfrastructureException, Advertisement>> {

    const result = await this.connection.query(`SELECT * FROM advertisements WHERE id = ? `, [id.value()])

    if (!result || result.length < 1) {
      return  left(ZeroRecordsException.build())
    }

    const row = result[0] as any;

    const passwordResult = await Password.fromEncryptedPassword(row.password);
    if (passwordResult._tag === 'Left') {
      return left(passwordResult.left);
    }

    const advertisementResult = Advertisement.build(
      row.id,
      row.description,
      passwordResult.right,
      new Date(row.advertisement_date)
    )

    if (advertisementResult._tag === 'Left') {
      return left(advertisementResult.left);
    }

    return right(advertisementResult.right);
  }

  async save(advertisement: Advertisement): Promise<Either<InfrastructureException, void>> {

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

    return right(undefined);
  }
}
