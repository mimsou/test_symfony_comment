var api = {}

api.call = async (method="POST",endpoint="/" ,data=[], callback,error)=>{
    var token = window.isConnected() ? localStorage.getItem('token') : '';
    $.ajax({
        type:method,
        url : endpoint,
        data:data,
        headers: {
            Authorization: 'Bearer '+ token
        },
        success: function (result, status, xhr) {
            if(typeof callback == 'function')  callback(result);

        },
        error: function (xhr, status, errors) {
            if(typeof error == 'function')   error(errors);
        }
    });
}