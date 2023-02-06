<?php

namespace Goxens\GoxensV2Php;

use Exception;
use Goxens\GoxensV2Php\GoxensV2Php\Endpoints;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Simplesend
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function sendSimpleSend($data, $token)
    {
        try {
            $headers = null;
            if (isset($token)) {
                if (substr($token, 0, 4) === 'GOX-') {
                    $headers = array(
                        'x-api-key:' . $token,
                        'Content-Type: application/json'
                    );
                } else {
                    $headers = array(
                        'Authorization: Bearer ' . $token,
                        'Content-Type: application/json'
                    );
                }
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => Endpoints::BASE_URL .'/api/simplesend/sms',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode([
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
                ]),
                CURLOPT_HTTPHEADER => $headers
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $responseData = json_decode($response, true);

            if ($responseData) {
                return $responseData;
            } else {
                throw new Exception("SimpleSend creation failed: " . $responseData);
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

}
