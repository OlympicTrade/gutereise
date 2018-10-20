<?php
namespace ReviewsAdmin\Model;

use Aptero\Db\Entity\Entity;

class Review extends Entity
{
    const STATUS_NEW       = 0;
    const STATUS_VERIFIED  = 1;
    const STATUS_REJECTED  = 2;

    static public $statuses = [
        self::STATUS_VERIFIED   => 'Проверен',
        self::STATUS_REJECTED   => 'Отклонен',
        self::STATUS_NEW        => 'Новый',
    ];

    public function __construct()
    {
        $this->setTable('reviews');

        $this->addProperties([
            'name'          => [],
            'review'        => [],
            'status'        => [],
            'source'        => [],
            'time_create'   => [],
        ]);
    }
}