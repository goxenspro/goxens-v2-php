<?php

namespace Goxens\GoxensV2Php;

use Exception;
use Goxens\GoxensV2Php\GoxensV2Php\Endpoints;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Typeuser
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getTypeId($type)
    {
        $response = $this->client->request('GET', Endpoints::BASE_URL . '/api/typeusers');
        $responseData = json_decode($response->getBody(), true);

        foreach ($responseData['data'] as $typeuser) {
            if ($typeuser['attributes']['libelle'] == $type) {
                return $typeuser['id'];
            }
        }

        throw new Exception("Type not found");
    }
}

// Example usage
/*$typeUser = new TypeUser();
$typeId = $typeUser->getTypeId('Particulier'); // Particulier, Professionnel
echo $typeId; // Outputs the ID of the "Particulier" type*/

