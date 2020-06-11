<?php


namespace App\Consts;


class ScheduleTypes
{
    const STATIC = 1;
    const DYNAMIC = 2;

    const TABLE = [
        self::STATIC => "Статическое",
        self::DYNAMIC => "Динамическое",
    ];
}
