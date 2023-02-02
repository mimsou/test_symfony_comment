var api = {}

api.call = async (method="POST",endpoint="/" ,data=[], callback,error)=>{
    $.ajax({
        type:method,
        url : endpoint,
        data:data,
        headers: {
            Authorization: 'Bearer '
        },
        success: function (result, status, xhr) {
            if(typeof callback == 'function')  callback(result);

        },
        error: function (xhr, status, errors) {
            if(typeof error == 'function')   error(errors);
        }
    });
}