<?php


namespace App\Consts;


class Roles
{
    const DIRECTOR = 1;
    const DIRECTOR_ASSISTANT = 2;
    const TEACHER = 3;
    const STUDENT = 4;
    const PARENT = 5;

    const TABLE = [
        self::DIRECTOR => "Директор",
        self::DIRECTOR_ASSISTANT => "Заместитель директора",
        self::TEACHER => "Учитель",
        self::STUDENT => "Ученик",
        self::PARENT => "Родитель",
    ];
}
