import { FrameworkRequest } from '../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../framework/FrameworkResponse';
import { UpdateAdvertisementCommand } from '../../application/update-advertisement/UpdateAdvertisementCommand';
import { UpdateAdvertisementUseCase } from '../../application/update-advertisement/UpdateAdvertisementUseCase';
import {CommonController} from "../../../common/ui/CommonController";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class UpdateAdvertisementController extends CommonController {

  constructor(
    private updateAdvertisementUseCase: UpdateAdvertisementUseCase
  ) {
    super();
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

      return this.processSuccessfulCommand()
    } catch (error: any) {
      if (error instanceof ReferenceError)
        return this.processNotFoundException(error)

      return this.processFailedCommand(error)
    }
  }
}
