<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JWTservice;
use App\Service\GlobalRestResponse;


#[Route('/auth')]
class GoogleAuthController extends AbstractController
{

    private $response;

    public function __construct(GlobalRestResponse $globalRespons,)
    {
        $this->response = $globalResponse;
    }
    #[Route('/google-auth', name: 'app_googleauth')]
    public function handleGoogleAuth(Request $request,JWTservice $jwt)
    {
        $idToken = $request->request->get('id_token');

        $client = new Client();
        $response = $client->get('https://oauth2.googleapis.com/tokeninfo?id_token=' . $idToken);

        $userData = json_decode($response->getBody()->getContents(), true);

        if (isset($data['error_description'])) {
            return new JsonResponse(['error' => $data['error_description']]);
        }

        $data = [
            'username' => $userData['email'],
            'roles' => ['ROLE_USER'],
            'token' => $jwt->encode([
                'username' => $userData['email'],
                'roles' => ['ROLE_USER'],
                'exp' => time() + 3600,
            ]),
        ];


        return $this->response->success($data);

    }
}
