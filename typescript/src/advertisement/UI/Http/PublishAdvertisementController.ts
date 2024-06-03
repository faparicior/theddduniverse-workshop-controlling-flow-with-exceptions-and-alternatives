import { FrameworkRequest } from '../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../framework/FrameworkResponse';
import { PublishAdvertisementCommand } from '../../application/publish-advertisement/PublishAdvertisementCommand';
import { PublishAdvertisementUseCase } from '../../application/publish-advertisement/PublishAdvertisementUseCase';

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class PublishAdvertisementController {

  constructor(
    private publishAdvertisementUseCase: PublishAdvertisementUseCase
  ) {
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

      return new FrameworkResponse(201)

    } catch (error: any) {
      return new FrameworkResponse(400, error.message)
    }
  }
}
