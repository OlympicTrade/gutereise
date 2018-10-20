<?php
namespace Reviews\Model;

use Aptero\Db\Entity\Entity;

class Review extends Entity
{
    const STATUS_NEW       = 0;
    const STATUS_VERIFIED  = 1;
    const STATUS_REJECTED  = 2;

    public function __construct()
    {
        $this->setTable('reviews');

        $this->addProperties([
            'name'          => [],
            'review'        => [],
            'answer'        => [],
            'status'        => [],
            'source'        => [],
            'time_create'   => [],
        ]);
    }
}