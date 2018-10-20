<?php
namespace Sync\Model;

class DbConstants
{
    static public $languages = [
        1  => 'Русский',
        2  => 'Немецкий',
        3  => 'Английский',
        4  => 'Французский',
        5  => 'Испанский',
        6  => 'Итальянский',
        10 => 'Китайский',
    ];

    static public $languagesDeclension = [
        1  => ['Русский', 'Русском', 'Русским'],
        2  => ['Немецкий', 'Немецком', 'Немецким'],
        3  => ['Английский', 'Английском', 'Английским'],
        4  => ['Французский', 'Французском', 'Французским'],
        5  => ['Испанский', 'Испанском', 'Испанским'],
        6  => ['Итальянский', 'Итальянском', 'Итальянским'],
        10 => ['Китайский', 'Китайском', 'Китайским'],
    ];

    const ERROR_DATA_DATES  	    = 1;
    const ERROR_DATA_TOURISTS       = 2;

    const ERROR_TRANSPORT_NOT_FOUND = 201;
    const ERROR_TRANSPORT_DURATION  = 202;
    const ERROR_TRANSPORT_CAPACITY  = 203;
    const ERROR_TRANSPORT_PRICE     = 204;
    const ERROR_TRANSPORT_TIME      = 205;

    const ERROR_MUSEUM_TICKETS      = 101;
    const ERROR_MUSEUM_WEEKEND      = 102;
    const ERROR_MUSEUM_WORKDAY      = 103;
    const ERROR_MUSEUM_WORKTIME     = 104;
    const ERROR_MUSEUM_TIME         = 105;
    const ERROR_MUSEUM_NOT_FOUND    = 106;

    const ERROR_EXCURSION_NOT_FOUND    = 301;
    const ERROR_EXCURSION_MIN_MAX_TIME = 302;

    static public $errors = [
        1 => 'Не указана дата',
        2 => 'Не указано количество туристов',

        //transport
        201 => 'Транспорт не найден',
        202 => 'Не указана длительность аренды',
        203 => 'Транспорт не может вместить столько туристов',
        204 => 'Ошибка расчета стоимости транспорта',
        205 => 'Не указано время аренды транспорта',

        //museums
        101 => 'Музей не работает в этот период года',
        102 => 'Выходной день в музее',
        103 => 'Выходной день в музее',
        104 => 'Выход за пределы рабочего времени музея',
        105 => 'Не указана длительность посещения музея',
        106 => 'Музей не найден в базе данных',

        //excursion
        301 => 'Экскурсия не найдена"',
        302 => 'Выход за временной предел экскурсии',
    ];
}