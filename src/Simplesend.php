<?php

namespace Goxens\GoxensV2Php;
use Exception;
use Goxens\GoxensV2Php\GoxensV2Php\Endpoints;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class Simplesend {

    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }


    /**
     * @throws Exception
     */
    private function createSimpleSend($data, $token)
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

            $response = $this->client->request('POST', Endpoints::BASE_URL . '/api/simplesends', [
                'headers' => $headers,
                'json' => $data
            ]);

            $responseData = json_decode($response->getBody(), true);
            if ($responseData['success']) {
                return $responseData['id'];
            } else {
                throw new Exception("SimpleSend creation failed: " . $responseData['message']);
            }
        } catch (GuzzleException $e) {
            error_log($e->getMessage());
            return false;
        }
    }


    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function sendSimpleSend($data, $token){

        $produit = new Produits();
        $produitId = $produit->getProduitId('SMS', $token);

        $createSimpleSendData = ['data' => []];
        $idSimpleSend = $this->createSimpleSend($createSimpleSendData, $token);

        if(!$idSimpleSend){
            throw new Exception("SimpleSend creation failed");
        }

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
            $response = $this->client->request('PUT', Endpoints::BASE_URL .'/api/simplesends' . $idSimpleSend , [
                'headers' => $headers,
                'form_params' => [
                    "data" => [
                        "produit" => $produitId,
                        $data
                    ]
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            if ($responseData['success']) {
                return $responseData['data'];
            } else {
                throw new Exception("SimpleSend creation failed: " . $responseData['message']);
            }

        } catch (GuzzleException $e) {
            error_log($e->getMessage());
            return false;
        }

    }


}
