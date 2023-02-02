$(document).ready(function() {
     commentModule.init($("#page-id").val() ?false : true  , null, "comments");
    commentModule.initGoogleAuth();
})