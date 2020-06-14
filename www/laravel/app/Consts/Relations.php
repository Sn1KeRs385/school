<?php


namespace App\Consts;


class Relations
{
    const MOTHER = 1;
    const FATHER = 2;
    const GRANDMOTHER = 3;
    const GRANDFATHER = 4;
    const TRUSTEE = 5;
    const BROTHER = 6;
    const SISTER = 7;

    const TABLE = [
        self::MOTHER => "Мать",
        self::FATHER => "Отец",
        self::GRANDMOTHER => "Бабушка",
        self::GRANDFATHER => "Дедушка",
        self::TRUSTEE => "Опекун",
        self::BROTHER => "Брат",
        self::SISTER => "Сестра",
    ];
}
