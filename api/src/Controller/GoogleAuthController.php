<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JWTservice;
use App\Service\GlobalRestResponse;
use Google_Client;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route('/auth')]
class GoogleAuthController extends AbstractController
{

    private $response;

    public function __construct(GlobalRestResponse $globalResponse)
    {
        $this->response = $globalResponse;
    }
    #[Route('/google-auth', name: 'app_googleauth')]
    public function handleGoogleAuth(Request $request,JWTservice $jwt,UserRepository $repo,JWTTokenManagerInterface $JWTManager)
    {
        $client = new Google_Client(['client_id' => '473502850114-hlm2upef1dgsdq675hja991uivhb75j9.apps.googleusercontent.com']);
        $payload = $client->verifyIdToken($request->get('id_token'));
        if ($payload) {

            $email = $payload["email"];
            $name = $payload["name"];
            $picture = $payload["picture"];

            if(empty($user = $repo->findOneBy(['email'=>$email]))){
                $user = New User();
                $user->setEmail($email);
                $user->setPicture($picture);
                $user->setName($name);
                $repo->save($user,true);
            }
            $token = $JWTManager->create($user);
            $serializer = new Serializer([new ObjectNormalizer()],  [new JsonEncoder()]);
            $serialisedUser = $serializer->serialize($user, 'json');

            return $this->response->success(['token' => $token,'user'=>json_decode($serialisedUser)]);

        } else {
            return $this->response->error("","Erruer d'authentification");
        }



    }
}
