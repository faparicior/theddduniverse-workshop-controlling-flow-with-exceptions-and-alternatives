import { FrameworkRequest } from '../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../framework/FrameworkResponse';
import { PublishAdvertisementCommand } from '../../application/publish-advertisement/PublishAdvertisementCommand';
import { PublishAdvertisementUseCase } from '../../application/publish-advertisement/PublishAdvertisementUseCase';
import {CommonController} from "../../../common/ui/CommonController";
import {BoundedContextException} from "../../../common/exceptions/BoundedContextException";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class PublishAdvertisementController extends CommonController {

  constructor(
    private publishAdvertisementUseCase: PublishAdvertisementUseCase
  ) {
    super();
  }
  async execute(req: AddAdvertisementRequest): Promise<FrameworkResponse> {

    try {
      const command = new PublishAdvertisementCommand(
          req.body.id,
          req.body.description,
          req.body.email,
          req.body.password
      )

      let result = await this.publishAdvertisementUseCase.execute(command)
      if (result.isSuccess())
        return this.processSuccessfulCreateCommand()

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
