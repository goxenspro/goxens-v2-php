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
            $response = $this->client->request('POST', Endpoints::BASE_URL . '/api/senders', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
                'form_params' => [
                    'data' => [
                        'name' => $name
                    ]
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);
            if ($responseData['success']) {
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
            $response = $this->client->request('PUT', Endpoints::BASE_URL . '/api/senders/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
                'form_params' => [
                    'data' => [
                        'hasDelete' => true
                    ]
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);
            if ($responseData['success']) {
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
            $response = $this->client->request('GET', Endpoints::BASE_URL . '/api/senders', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);
            if ($responseData['success']) {
                return $responseData['data'];
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
