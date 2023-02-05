$(document).ready(function() {
     commentModule.init($("#page-id").val() ?false : true  , null, "comments");
})
window. handleCredentialResponse = function (resp){
    console.log('yes'+resp)
    console.log(resp);
}