<?php

namespace Museums\Service;

use Aptero\Service\AbstractService;
use Museums\Model\Museum;
use Museums\Model\Tags;

class MuseumsService extends AbstractService
{
    public function getTag($filters = [])
    {
        $types = new Tags();

        if($filters['url']) {
            $types->select()->where(['url' => $filters['url']]);
        }

        return $types;
    }

    public function getPaginator($page, $filters = [], $itemsPerPage = 999)
    {
        $filters['join'] = [
            'image'
        ];

        $museums = Museum::getEntityCollection();
        $museums->setSelect($this->getMuseumsSelect($filters));

        return $museums->getPaginator($page, $itemsPerPage);
    }

    public function getMuseumsSelect($filters = [])
    {
        $select = $this->getSql()->select()
            ->from(['t' => 'museums'])
            ->group('t.id');

        $select->where(['active' => 1]);

        if (in_array('image', $filters['join'])) {
            $select
                ->join(['ei' => 'museums_images'], 't.id = ei.depend', ['image-id' => 'id', 'image-filename' => 'filename'], 'left');
        }

        if(!empty($filters['url'])) {
            $select->where(['t.url' => $filters['url']]);
        }

        if(!empty($filters['query'])) {
            $select->where->like('t.name',  '%' . $filters['query'] . '%');
        }

        if(!empty($filters['tag'])) {
            $ids[] = $filters['tag']->getId();

            foreach ($filters['tag']->getChildren() as $subTag) {
                $ids[] = $subTag->getId();
            }

            $select
                ->join(['mtt' => 'museums_mtt'], 't.id = mtt.depend', [], 'left')
                ->where(['mtt.tag_id' => $ids]);
        }

        return $select;
    }

    /*public function getMuseums()
    {
        $museums = Museum::getEntityCollection();

        $museums->select()->where(['active' => 1]);

        return $museums;
    }*/

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