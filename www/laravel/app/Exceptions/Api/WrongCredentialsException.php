<?php

namespace App\Exceptions\Api;

use Exception;

class WrongCredentialsException extends BaseBusinessException
{
    public function render()
    {
        return getJson(null, [
            [
                'code' => 422,
                'message' => 'WRONG_CREDENTIALS',
                'description' => 'Wrong credentials',
            ],
        ]);
    }
}
