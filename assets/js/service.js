var commentService = {}

commentService.throwError = (error) => {
    alert("une erreur c'est produite "+error);
}

commentService.getLastComments = (callback) => {
    api.call("GET",api_url+"/comment/get-last-comment","", function(result){
        console.log(result)
        if(typeof callback == 'function')  callback(result.data ? result.data : []);
    },function(error){
        if(typeof callback == 'function')  callback([]);
        commentService.throwError(error)
    })
}

commentService.getCommentsFromPageId = (pageId,callback) => {
    var data = {pageId:pageId}
    api.call("GET",api_url+"/comment/get-comment",data, function(result){
        if(typeof callback == 'function')  callback(result.data ? result.data : []);
    },function(error){
        if(typeof callback == 'function')  callback([]);
        commentService.throwError(error)
    })
}

commentService.addComment = (  parentId,commentText,pageId,user,callback) => {
    var data = {
        parentId:parentId,
        commentText:commentText,
        pageId:pageId,
        user:user
    }
    api.call("POST",api_url+"/comment/add-comment",data, function(result){
        if(typeof callback == 'function')  callback(result);
    },function(error){
        if(typeof callback == 'function')  callback([]);
        commentService.throwError(error)
    })
}

commentService.deleteComment = (pageId) => {

}

commentService.rateComment = (pageId) => {

}

