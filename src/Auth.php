<?php

namespace Goxens\GoxensV2Php;
use Exception;
use Goxens\GoxensV2Php\GoxensV2Php\Endpoints;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class Auth {

    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws Exception
     */
    public function generateToken($email, $password)
    {
        try {
            $response = $this->client->request('POST', Endpoints::BASE_URL . '/api/auth/local', [
                'form_params' => [
                    'identifier' => $email,
                    'password' => $password,
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);
            if ($responseData['jwt']) {
                return $responseData['jwt'];
            } else {
                throw new Exception("Token generation failed: " . $responseData['message']);
            }

        } catch (GuzzleException $e) {
            // Log the error and return false
            error_log($e->getMessage());
            return false;
        }
    }


}