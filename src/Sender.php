<?php

namespace Goxens\GoxensV2Php;

use Exception;
use Goxens\GoxensV2Php\GoxensV2Php\Endpoints;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Sender
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     */
    public function createSender($name, $token): string
    {
        try {
            $headers = [];
            if (isset($token)) {
                // Vérifiez si c'est une clé API ou un jeton JWT
                if (substr($token, 0, 4) === 'GOX-') {
                    $headers = [
                        'x-api-key' => $token
                    ];
                } else {
                    $headers = [
                        'Authorization' => 'Bearer ' . $token
                    ];
                }
            }
            $response = $this->client->request('POST', Endpoints::BASE_URL . '/api/senders', [
                'headers' => $headers,
                'form_params' => [
                    'data' => [
                        'name' => $name
                    ]
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);
            if ($responseData['status'] === 'Pending') {
                return "Sender created and awaiting validation.";
            } else {
                throw new Exception("Sender creation failed: " . $responseData['message']);
            }
        } catch (Exception $e) {
            // Log the error and return error message
            error_log($e->getMessage());
            return "An error occurred while creating the sender: " . $e->getMessage();
        }
    }

    /**
     * @throws Exception
     */
    public function deleteSender($id, $token): string
    {
        try {
            $headers = [];
            if (isset($token)) {
                // Vérifiez si c'est une clé API ou un jeton JWT
                if (substr($token, 0, 4) === 'GOX-') {
                    $headers = [
                        'x-api-key' => $token
                    ];
                } else {
                    $headers = [
                        'Authorization' => 'Bearer ' . $token
                    ];
                }
            }
            $response = $this->client->request('PUT', Endpoints::BASE_URL . '/api/senders/' . $id, [
                'headers' => $headers,
                'form_params' => [
                    'data' => [
                        'hasDelete' => true
                    ]
                ]
            ]);
            $responseData = json_decode($response->getBody(), true);
            if ($responseData['hasDelete'] === true ){
                return "Sender deleted successfully.";
            } else {
                throw new Exception("Delete sender failed: " . $responseData['message']);
            }
        } catch (GuzzleException $e) {
            // Log the error and return false
            error_log($e->getMessage());
            return "An error occurred while trying to delete the sender.";
        }
    }

    /**
     * @throws Exception
     */
    public function findSenders($token)
    {
        try {
            $headers = [];
            if (isset($token)) {
                // Vérifiez si c'est une clé API ou un jeton JWT
                if (substr($token, 0, 4) === 'GOX-') {
                    $headers = [
                        'x-api-key' => $token
                    ];
                } else {
                    $headers = [
                        'Authorization' => 'Bearer ' . $token
                    ];
                }
            }

            $response = $this->client->request('GET', Endpoints::BASE_URL . '/api/senders', [
                'headers' => $headers
            ]);

            $responseData = json_decode($response->getBody(), true);
            if (isset($responseData['results']) && !empty($responseData['results'])) {
                return $responseData['results'];
            } else {
                throw new Exception("Senders not found: " . $responseData['message']);
            }

        } catch (GuzzleException $e) {
            // Log the error and return false
            error_log($e->getMessage());
            return false;
        }
    }



}
