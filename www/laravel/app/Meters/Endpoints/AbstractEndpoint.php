<?php

namespace App\Meters\Endpoints;

abstract class AbstractEndpoint
{
    public function getApiUrl()
    {
        return "https://api.strij.cloud/api/";
    }

    public function getEndpoint()
    {
        return "";
    }

    public function getAccessToken() {
        return "7c5963b9db537ebd300fcb94b70ddd4b97d263bb846c3cd7390769576c2d9c50";
    }

    public function makeUrl()
    {
        return "{$this->getApiUrl()}{$this->getEndpoint()}";
    }
}
