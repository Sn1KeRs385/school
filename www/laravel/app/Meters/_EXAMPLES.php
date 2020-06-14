<?php 

use App\Meter\MeterService;
use App\Meter\Endpoints\GetDevices;


$access_token = "7c5963b9db537ebd300fcb94b70ddd4b97d263bb846c3cd7390769576c2d9c50";


// Получить список устройств
$devicesEndpoint = new GetDevices($access_token);
$meterService = new MeterService($devicesEndpoint);
$result = $meterService->do();

