import { FrameworkRequest } from '../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../framework/FrameworkResponse';
import {RenewAdvertisementUseCase} from "../../application/renew-advertisement/RenewAdvertisementUseCase";
import {RenewAdvertisementCommand} from "../../application/renew-advertisement/RenewAdvertisementCommand";
import {CommonController} from "../../../common/ui/CommonController";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class RenewAdvertisementController extends CommonController {

  constructor(
    private renewAdvertisementUseCase: RenewAdvertisementUseCase
  ) {
    super();
  }
  async execute(req: AddAdvertisementRequest): Promise<FrameworkResponse> {
    try {
      const command = new RenewAdvertisementCommand(
          req.param,
          req.body.password
      )

      await this.renewAdvertisementUseCase.execute(command)

      return this.processSuccessfulCommand()
    } catch (error: any) {
      if (error instanceof ReferenceError)
        return this.processNotFoundException(error)

      return this.processFailedCommand(error)
    }
  }
}
