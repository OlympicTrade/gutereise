<?php

namespace Museums\Service;

use Aptero\Service\AbstractService;
use Museums\Model\Museum;
use Museums\Model\Point;

class MuseumsService extends AbstractService
{
    public function getMuseums()
    {
        $museums = Museum::getEntityCollection();

        return $museums;
    }

    public function getMuseum($filters = [])
    {
        $museum = new Museum();

        $museum->select()
            ->where(['url' => $filters['url']]);

        return $museum;
    }

    public function getPoint($filters = [])
    {
        $point = new Point();

        $point->select()
            ->where(['url' => $filters['url']]);

        return $point;
    }
}