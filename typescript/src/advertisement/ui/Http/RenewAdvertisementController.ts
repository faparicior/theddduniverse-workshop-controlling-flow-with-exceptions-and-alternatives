import { FrameworkRequest } from '../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../framework/FrameworkResponse';
import {RenewAdvertisementUseCase} from "../../application/renew-advertisement/RenewAdvertisementUseCase";
import {RenewAdvertisementCommand} from "../../application/renew-advertisement/RenewAdvertisementCommand";
import {CommonController} from "../../../common/ui/CommonController";
import {BoundedContextException} from "../../../common/exceptions/BoundedContextException";

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

      const result = await this.renewAdvertisementUseCase.execute(command)

      if (result.isSuccess())
        return this.processSuccessfulCommand()

      if (result.getError() instanceof BoundedContextException)
        return this.processDomainOrApplicationExceptionResponse(result.getError() as BoundedContextException)

      return this.processFailedCommand(result.getError() as Error)
    } catch (error: any) {
      switch (true) {
        case error instanceof BoundedContextException:
          return this.processDomainOrApplicationExceptionResponse(error)
        default:
          return this.processFailedCommand(error)
      }
    }
  }
}
