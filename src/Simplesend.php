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
            $response = $this->client->request('POST', Endpoints::BASE_URL . '/api/simplesends', [
                'headers' => [
                    'Authorization' => "Bearer " . $token,
                    'Content-Type' => 'application/json'
                ],
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
            $response = $this->client->request('PUT', Endpoints::BASE_URL .'/api/simplesends' . $idSimpleSend , [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
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
