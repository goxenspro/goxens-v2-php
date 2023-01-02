<?php

namespace Goxens\GoxensV2Php;

use Exception;
use Goxens\GoxensV2Php\GoxensV2Php\Endpoints;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class Produits {

        private Client $client;

        public function __construct()
        {
            $this->client = new Client();
        }

    /**
     * @throws GuzzleException
     * @throws Exception
     */

    public function getProduitId($type, $token)
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
            $response = $this->client->request('GET', Endpoints::BASE_URL . '/api/produits', [
                'headers' => $headers
            ]);
            $responseData = json_decode($response->getBody(), true);

            foreach ($responseData['data'] as $typeproduit) {
                if ($typeproduit['attributes']['libelle'] == $type) {
                    return $typeproduit['id'];
                }
            }

            throw new Exception("Type not found");
        } catch (Exception $e) {
            // Log the error and return false
            error_log($e->getMessage());
            return false;
        }
    }

}