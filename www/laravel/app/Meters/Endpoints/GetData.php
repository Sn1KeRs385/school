<?php

namespace App\Meters\Endpoints;

use App\Meters\Endpoints\AbstractEndpoint;

class GetData extends AbstractEndpoint
{
    private $modem_id = null;

    public function __construct(string $modem_id)
    {
        $this->modem_id = $modem_id;
    }

    public function getEndpoint()
    {
        return "data";
    }

    public function makeUrl()
    {
        $params = [
            "access-token={$this->getAccessToken()}",
            "modem_id={$this->modem_id}",
            "channel=1",
        ];

        $strParams = implode("&", $params);

        return "{$this->getApiUrl()}{$this->getEndpoint()}?{$strParams}";
    }
}
