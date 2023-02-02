<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;

class GlobalRestResponse
{

     public function success(array $data = null , $message='succes' , int $responseCode=200) :JsonResponse{
         return new JsonResponse([
             'status'=>'1',
             'messsage'=> $message,
             'data'=> $data

         ],$responseCode);
      }

    public function error(array $data = null , $message='succes' , int $responseCode=500) :JsonResponse{
        return new JsonResponse([
            'status'=>'0',
            'messsage'=> $message,
            'data'=> $data

        ],$responseCode);
    }

}