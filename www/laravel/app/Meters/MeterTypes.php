<?php

namespace App\Meters;

class MeterTypes
{
    const ELECTRIC = 1;
    const GAS = 2;
    const HEAT = 3;
    const COLD_WATER = 4;
    const HOT_WATER = 5;
    const COLD_AND_HOT_WATER = 6;

    const TYPES =
        [
            'ELECTRIC' => [self::ELECTRIC],
            'GAS' => [self::GAS],
            'HEAT' => [self::HEAT],
            'WATER' => [self::COLD_WATER, self::HOT_WATER, self::COLD_AND_HOT_WATER],
        ];

    static public function getAllowedIdsForHouseArray($house)
    {
        $meter_type_ids = [];

        if ($house->is_electric_meter_data_pass) {
            $meter_type_ids = array_merge($meter_type_ids, self::TYPES['ELECTRIC']);
        }
        if ($house->is_gas_meter_data_pass) {
            $meter_type_ids = array_merge($meter_type_ids, self::TYPES['GAS']);
        }
        if ($house->is_heat_meter_data_pass) {
            $meter_type_ids = array_merge($meter_type_ids, self::TYPES['HEAT']);
        }
        if ($house->is_water_meter_data_pass) {
            $meter_type_ids = array_merge($meter_type_ids, self::TYPES['WATER']);
        }
        return $meter_type_ids;
    }
}
