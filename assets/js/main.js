$(document).ready(function() {

    if(window.isConnected()) {
        $('.logout_action').show()
        $('.social_warp').hide()
        $(".button_avatar").prop("src",JSON.parse(localStorage.getItem('user')).picture)
    }  else{
        $('.logout_action').hide()
        $('.social_warp').show();
    }
    commentModule.init($("#page-id").val() ?false : true  , null, "comments");
    $(".logout_action").on("click", function(){
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        commentModule.reload();
    })
})
window.handleCredentialResponse = function (resp){
    api.call('POST',api_url+'/auth/google-auth',{
        id_token: resp.credential
    },function(resp){
        if(resp?.data?.token){
          var token = resp.data.token;
          localStorage.setItem('token', token);
          $('.social_warp').hide();
            $('.logout_action').show()
          console.log(JSON.stringify(resp.data.user));
          localStorage.setItem('user', JSON.stringify(resp.data.user));
          commentModule.reload();
        }
    })
}

window.parseJwt = function  (token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}