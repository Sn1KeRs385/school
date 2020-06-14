<?php

namespace App\Exceptions\Api;

use Exception;

abstract class BaseBusinessException extends Exception
{
    abstract public function render();
}
