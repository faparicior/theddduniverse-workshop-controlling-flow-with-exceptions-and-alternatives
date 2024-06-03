import { FrameworkRequest } from '../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../framework/FrameworkResponse';
import {RenewAdvertisementUseCase} from "../../application/renew-advertisement/RenewAdvertisementUseCase";
import {RenewAdvertisementCommand} from "../../application/renew-advertisement/RenewAdvertisementCommand";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class RenewAdvertisementController {

  constructor(
    private renewAdvertisementUseCase: RenewAdvertisementUseCase
  ) {
  }
  async execute(req: AddAdvertisementRequest): Promise<FrameworkResponse> {
    try {
      const command = new RenewAdvertisementCommand(
          req.param,
          req.body.password
      )

      await this.renewAdvertisementUseCase.execute(command)

      return new FrameworkResponse(200)
    } catch (error: any) {
      if (error instanceof ReferenceError)
        return new FrameworkResponse(404, error.message)

      return new FrameworkResponse(400, error.message)
    }
  }
}
