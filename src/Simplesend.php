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
     * @throws GuzzleException
     */
    public function sendSimpleSend($data, $token){
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
            $response = $this->client->request('POST', Endpoints::BASE_URL .'/api/simplesend/sms', [
                'headers' => $headers,
                'form_params' => [
                    "data" => [
                        "produit" => 1,
                        "sender" => $data['sender'],
                        "typeContact" => $data['typeContact'],
                        "listeContacts" => $data['listeContacts'],
                        "message" => $data['message'],
                        "hasSchedule" => $data['hasSchedule'],
                        "programDate" => $data['programDate'],
                        "programTime" => $data['programTime'],
                        "typeSmsSend" => $data['typeSmsSend']
                    ]
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            if ($responseData) {
                return $responseData['data'];
            } else {
                throw new Exception("SimpleSend creation failed: " . $responseData);
            }

        } catch (GuzzleException $e) {
            error_log($e->getMessage());
            return false;
        }

    }


}
