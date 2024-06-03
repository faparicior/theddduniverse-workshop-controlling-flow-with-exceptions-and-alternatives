import {FrameworkResponse} from "../../framework/FrameworkResponse";


export class CommonController {
    protected processSuccessfulCommand(): FrameworkResponse {
        return new FrameworkResponse(
            200,
            {
                errors: '',
                code: 200,
                message: ''
            })
    }

    protected processSuccessfulCreateCommand(): FrameworkResponse {
        return new FrameworkResponse(
            201,
            {
                errors: '',
                code: 201,
                message: ''
            })
    }

    // failed

    protected processFailedCommand(error: any): FrameworkResponse {
        return new FrameworkResponse(
            400,
            {
                errors: error.message,
                code: 400,
                message: error.message
            })
    }

    protected processNotFoundException(error: any): FrameworkResponse {
        return new FrameworkResponse(
            404,
            {
                errors: error.message,
                code: 404,
                message: error.message
            })
    }
}