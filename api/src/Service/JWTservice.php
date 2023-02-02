<?php

namespace App\Service;

use \Firebase\JWT\JWT;

class JWTservice
{

    private $algorithm = "ES384";

    public function encode(array $data)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // 1 hour

        $payload = [
            'iss' => 'myApp',
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data
        ];

        return JWT::encode($payload, "ddd", $this->algorithm);
    }

    public function decode($jwt)
    {
        return (array) JWT::decode($jwt, $this->secretKey, [$this->algorithm]);
    }
}