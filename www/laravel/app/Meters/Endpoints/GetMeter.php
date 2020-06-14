<?php

namespace App\Meters\Endpoints;

use App\Meters\Endpoints\AbstractEndpoint;

class GetMeter extends AbstractEndpoint
{
    private $access_token = null;
    private $modem_id = null;

    public function __construct(string $modem_id)
    {
        $this->modem_id = $modem_id;
    }

    public function getEndpoint()
    {
        return "meter";
    }

    public function makeUrl()
    {
        $params = [
            "access-token={$this->access_token}",
            "modem_id={$this->modem_id}",
        ];

        $strParams = implode("&", $params);

        return "{$this->getApiUrl()}{$this->getEndpoint()}?{$strParams}";
    }
}
