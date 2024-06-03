import { FrameworkRequest } from '../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../framework/FrameworkResponse';
import { UpdateAdvertisementCommand } from '../../application/update-advertisement/UpdateAdvertisementCommand';
import { UpdateAdvertisementUseCase } from '../../application/update-advertisement/UpdateAdvertisementUseCase';
import {RenewAdvertisementCommand} from "../../application/renew-advertisement/RenewAdvertisementCommand";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class UpdateAdvertisementController {

  constructor(
    private updateAdvertisementUseCase: UpdateAdvertisementUseCase
  ) {
  }
  async execute(req: AddAdvertisementRequest): Promise<FrameworkResponse> {
    try {
      const command = new UpdateAdvertisementCommand(
          req.param,
          req.body.description,
          req.body.email,
          req.body.password
      )

      await this.updateAdvertisementUseCase.execute(command)

      return new FrameworkResponse(200)
    } catch (error: any) {
      if (error instanceof ReferenceError)
        return new FrameworkResponse(404, error.message)

      return new FrameworkResponse(400, error.message)
    }
  }
}
