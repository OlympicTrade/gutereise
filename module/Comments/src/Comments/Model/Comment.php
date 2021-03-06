<?php
namespace Comments\Model;

use Aptero\Db\Entity\Entity;

class Comment extends Entity
{
    const TYPE_EXCURSION = 1;
    const TYPE_TOUR      = 2;
    const TYPE_TRANSPORT = 3;
    const TYPE_MUSEUM    = 4;

    public function __construct()
    {
        $this->setTable('comments');

        $this->addProperties([
            'name'         => [],
            'depend_id'    => [],
            'depend_type'  => [],
            'contact'      => [],
            'question'     => [],
            'answer'       => [],
            'send'         => ['default' => 1],
            'lang_code'    => [],
            'status'       => [],
            'time_create'  => [],
        ]);
    }
}