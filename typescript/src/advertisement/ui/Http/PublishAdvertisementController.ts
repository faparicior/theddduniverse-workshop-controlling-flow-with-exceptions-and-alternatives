import { FrameworkRequest } from '../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../framework/FrameworkResponse';
import { PublishAdvertisementCommand } from '../../application/publish-advertisement/PublishAdvertisementCommand';
import { PublishAdvertisementUseCase } from '../../application/publish-advertisement/PublishAdvertisementUseCase';
import {CommonController} from "../../../common/ui/CommonController";

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

      await this.publishAdvertisementUseCase.execute(command)

      return this.processSuccessfulCreateCommand()
    } catch (error: any) {
      return this.processFailedCommand(error)
    }
  }
}
