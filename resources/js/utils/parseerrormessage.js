export function ParseError(error)
{
    let response = error.response;
    let errStatus = response.status;
    let message = error.message;
    let typeMessage = 'error';
    let data = '';
    let errors = '';

    switch(errStatus){
        case 422:
            if(response.hasOwnProperty('data')) {
                data = response.data;
                if(data.hasOwnProperty('message')) {
                    message = data.message;
                }
                if(data.hasOwnProperty('errors')) {
                    errors = data.errors;
                    for(let p in errors) {
                        if(errors.hasOwnProperty(p)) {
                            message += '<br><br>' + p + ' ' + errors[p];
                        }
                    }
                }
            }
            break;
        case 401:
            message = '';
            break;
        default:
            break;
    }

    return {
        message: message,
        type: typeMessage,
        status: errStatus,
    };
}