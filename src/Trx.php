<?php

namespace Goxens\GoxensV2Php;
use Exception;
use Goxens\GoxensV2Php\GoxensV2Php\Endpoints;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class Trx{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
     public function getBalance($token){

         try{
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

            $response = $this->client->request('GET', Endpoints::BASE_URL .'/api/balance', [
                'headers' => $headers
            ]);

            $responseData = json_decode($response->getBody(), true);
            if ($responseData) {
                return $responseData;
            } else {
                throw new Exception("Balance retrieval failed: " . $responseData);
            }


         }catch(Exception $e){
             // Log the error and return error message
             error_log($e->getMessage());
             return "An error occurred while retrieving the balance: " . $e->getMessage();
         }
     }
}