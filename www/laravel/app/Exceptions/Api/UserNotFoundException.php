<?php

namespace App\Exceptions\Api;

use Exception;

class UserNotFoundException extends BaseBusinessException
{
    public function render()
    {
        return getJson(null, [
            [
                'code' => 422,
                'message' => 'USER_NOT_FOUND',
                'description' => 'User not found',
            ],
        ]);
    }
}
