<?php

namespace App\Meters;

use App\Meters\Endpoints\AbstractEndpoint;

class MeterService
{
    private $endpoint;

    public function __construct(AbstractEndpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function send()
    {
        $url = $this->endpoint->makeUrl();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($response, true);
        return $json;
    }

}