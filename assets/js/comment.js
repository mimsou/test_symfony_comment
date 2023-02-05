   var commentModule = {}
   commentModule.data = [];
   commentModule.config = {}
   commentModule.reload = ()=>{
      location.reload()
   }
   commentModule.template = {
      getSingleComment: (data,isParent = true) => {
         console.log(data);
         return `<div  class="comment-main-warp ${data.isTopLevel ? "comment-top-lvl" : "comment-top-child"}" data-id="${data.id}">
                     <div class="comment-picture"><img src="${data?.authorId?.picture ?? 'assets/img/anonyme.png'}" ></div>
                     <div class="comment-content-warp">
                         <div class="comment-author">${data?.authorId?.name  ?? 'name'}</div>
                         <div class="comment-text">${data.commentText}</div>
                         <div class="comment-action">
                            <span class="comment-rating-number">${data.commentRating ?? 0}</span>
                            <button class="comment-rating-up no-btn"> <i class="arrow up"></i></button>  |  <button class="comment-rating-down no-btn"> <i class="arrow down"></i> </button>  .
                            ${ isParent ? `
                            <button class="comment-response btn"> Repondre </button>
                            <div class="comment-response-form-warp" > 
                                
                            </div>` : ''}
                              <div class="comment-response-content">
                                   ${
                                         data?.responses ? data.responses.map((singleData, index) => {
                                            return commentModule.template.getSingleComment(singleData,false);
                                         }) : ''
                                    }
                              </div>
                         </div>
                     </div>
                 </div>
                <div class="captcha"></div>`;
      },
      getCommentForm: (parentComment=null) => {
         return `<div class="comment-form"">
                  <img class="comment-avatar" src="${ localStorage.getItem('user') ?  JSON.parse(localStorage.getItem('user')).picture : ''}" width="50px">
                  <textarea id="comment-form-inp"   ></textarea>
                  <button type="button" class="comment-form-submit btn"  data-parent="${parentComment ? parentComment : ''}"> submit </button>
                 </div>`;
      }
   }


   commentModule.setEvents = ()=>{
         $("body").on("click",".comment-form-submit" , function(){
            if(!isConnected()){

            }
            var parentComment = $(this).data("parent") != "" ? $(this).data("parent") : null;
            var textComment = $(this).parents(".comment-form").find("#comment-form-inp").val();
            var elm = $(this).parents(".comment-form")
            var pageId = $("#page-id").val();
            var user = JSON.parse(localStorage.getItem('user'));
            commentService.addComment(parentComment,textComment,pageId,user,function(){
               if (parentComment) elm.remove();
               commentModule.reload();
            })
         })

        $("body").on("click",".comment-response" , function(){
           var parentId = $(this).parents(".comment-main-warp").data("id");
          $(this).parents(".comment-main-warp").find(".comment-response-form-warp").html(
             commentModule.template.getCommentForm(parentId)
          );
      })
      $(".captcha").captcha();
      window.confSetEvent=true;
   }

   commentModule.initGloablView = () => {
      commentService.getLastComments(function(data){
         commentModule.data = data;
         commentModule.render(false);
      });
   }

   commentModule.render = (form = true) => {
      var data = commentModule.data;
      var markup = "";
      var markup = form ? commentModule.template.getCommentForm() : `<div>Déniers Commentaires</div>`;
      markup += data.map((singleData, index) => {
         return commentModule.template.getSingleComment(singleData);
      });
      $('#' + commentModule.config.selector).html(markup)
      if(!window.confSetEvent) {commentModule.setEvents();}
   }



   commentModule.init = (isGlobaleView = false, articleId, selector) => {
      commentModule.config = {articleId: articleId, selector: selector,setEvent:false}
      if (isGlobaleView) {
         commentModule.initGloablView();
         return;
      }
      var pageId = $("#page-id").val();
      commentService.getCommentsFromPageId(pageId,function(data){
         commentModule.data = data;
         commentModule.render(data);
      });
      commentModule.render();
   }



