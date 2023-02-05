<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GlobalRestResponse;
use App\Repository\CommentsRepository;
#[Route('/comment')]
class CommentController extends AbstractController
{
    private $response;


    public function __construct(GlobalRestResponse $globalResponse)
    {
       $this->response = $globalResponse;
    }

    #[Route('/get-last-comment', name: 'app_lastcomment')]
    public function getLastComments(Request $request,CommentsRepository $repo,UserRepository $userRepo): Response
    {
        $comments = $repo->getLastComments();
        return $this->response->success($comments);
    }

    #[Route('/get-comment', name: 'app_comment')]
    public function getComments(Request $request,CommentsRepository $repo,UserRepository $userRepo): Response
    {
        $comments = array($request->get("pageId"));
        if(!empty($request->get("pageId"))) {
            $comments = $repo->getCommentByPageId($request->get("pageId"));
        }
        return $this->response->success($comments);
    }



    #[Route('/add-comment', name: 'app_comments')]
    public function addComment(Request $request,CommentsRepository $repo,UserRepository $userRepo): Response
    {
        $comment = new Comments();
        $comment->setCommentText($request->get("commentText"));
        $comment->setAuthorId($userRepo->find($request->get("user")["id"]));
        $comment->setPageId($request->get("pageId"));
        $comment->setCreatDateTime(new \DateTime('now'));
        $comment->setParentId($repo->find($request->get("parentId")));
        $repo->save($comment,true);
        return $this->response->success([],"Comment saved !");
    }



    #[Route('/add-comment-rating', name: 'app_Comment')]
    public function addCommentRating(): Response
    {
        return $this->response->success();
    }
}
