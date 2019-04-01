<?php

namespace Museums\Service;

use Aptero\Service\AbstractService;
use Museums\Model\Museum;
use Museums\Model\Attraction;

class MuseumsService extends AbstractService
{
    public function getMuseums()
    {
        $museums = Museum::getEntityCollection();

        $museums->select()->where(['active' => 1]);

        return $museums;
    }

    public function getMuseum($filters = [])
    {
        $museum = new Museum();

        $museum->select()
            ->where([
                'url_path' => '/' . $filters['url'] . '/',
                'active' => 1,
            ]);

        return $museum;
    }

    /*public function getAttraction($filters = [])
    {
        $point = new Attraction();

        $point->select()
            ->where(['url' => $filters['url']]);

        return $point;
    }*/
}