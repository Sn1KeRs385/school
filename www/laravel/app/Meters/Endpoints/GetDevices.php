<?php

namespace App\Meters\Endpoints;

use App\Meters\Endpoints\AbstractEndpoint;

class GetDevices extends AbstractEndpoint
{
    private $access_token = null;
    private $endpoint = 'devices';

    public function __construct()
    {
        
    }

    public function getEndpoint()
    {
        return "devices";
    }

    public function makeUrl()
    {
        $params = [
            "access-token={$this->getAccessToken()}",
        ];

        $strParams = implode("&", $params);

        return "{$this->getApiUrl()}{$this->getEndpoint()}?{$strParams}";
    }
}
