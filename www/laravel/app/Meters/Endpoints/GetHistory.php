<?php

namespace App\Meters\Endpoints;

use App\Meters\Endpoints\AbstractEndpoint;
use Carbon\Carbon;

class GetHistory extends AbstractEndpoint
{
    private $modem_id = null;
    private $channel = null;
    private $start = null;

    public function __construct(string $modem_id, string $channel, Carbon $start = null)
    {
        $this->modem_id = $modem_id;
        $this->channel = $channel;
        $this->start = $start;
    }

    public function getEndpoint()
    {
        return "history";
    }

    public function makeUrl()
    {
        $params = [
            "access-token={$this->getAccessToken()}",
            "modem_id={$this->modem_id}",
            "channel={$this->channel}",
        ];

        if ($this->start) {
            $params[] = "start={$this->start->format('Y-m-d H:i')}";
        }

        $strParams = implode("&", $params);

        return "{$this->getApiUrl()}{$this->getEndpoint()}?{$strParams}";
    }
}
