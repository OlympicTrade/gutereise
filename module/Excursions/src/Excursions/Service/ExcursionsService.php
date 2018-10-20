<?php

namespace Excursions\Service;

use Aptero\Service\AbstractService;
use Excursions\Model\Excursion;
use Excursions\Model\ExcursionType;
use Sync\Model\DbConstants;
use Sync\Model\Sync;
use Zend\Json\Json;

class ExcursionsService extends AbstractService
{
    public function getType($filters = [])
    {
        $types = new ExcursionType();

        if($filters['url']) {
            $types->select()->where(['url' => $filters['url']]);
        }

        return $types;
    }

    public function getPaginator($page, $filters = [], $itemsPerPage = 1)
    {
        $filters['join'] = [
            'image'
        ];

        $excursions = Excursion::getEntityCollection();
        $excursions->setSelect($this->getExcursionsSelect($filters));

        return $excursions->getPaginator($page, $itemsPerPage);
    }

    public function getExcursionsSelect($filters = [])
    {
        $select = $this->getSql()->select()
            ->from(['t' => 'excursions'])
            ->group('t.id');

        if (in_array('image', $filters['join'])) {
            $select
                ->join(['ei' => 'excursions_images'], 't.id = ei.depend', ['image-id' => 'id', 'image-filename' => 'filename'], 'left');
        }

        if(!empty($filters['url'])) {
            $select->where(['t.url' => $filters['url']]);
        }

        if(!empty($filters['type'])) {
            $select
                ->join(['ett' => 'excursions_ett'], 't.id = ett.depend', [], 'left')
                ->where(['ett.type_id' => $filters['type']]);
        }

        if(!empty($filters['museums'])) {
            $select
                ->join(['etm' => 'excursions_museums'], 't.id = etm.depend', [], 'left')
                ->where(['etm.museum_id' => $filters['museums']]);
        }

        return $select;
    }

    public function getExcursions($filters = [])
    {
        $excursions = Excursion::getEntityCollection();

        if($filters['url']) {
            $excursions->select()->where(['url' => $filters['url']]);
        }

        return $excursions;
    }

    public function getExcursion($filters = [])
    {
        $excursion = new Excursion();

        $excursion->select()
            ->where(['url' => $filters['url']]);

        return $excursion;
    }

    public function getPrice($data)
    {
        if(!$data['date'] || !$data['lang_id'] || !$data['adults']) {
            return ['errors' => ['Заполните форму для рассчета стоимости']];
        }

        $data['excursion_id'] = $data['db_excursion_id'];
        unset($data['db_excursion_id']);

        $sync = new Sync();
        $resp = $sync->load('get-price', $data);

        $errors = [];

        foreach ($resp->summary->errors as $code => $error) {
            if($code == DbConstants::ERROR_MUSEUM_TICKETS) {
                $errors[] = $data['date'] . ' музей не работает';
                break;
            }

            $errors[] = DbConstants::$errors[$code];
        }

        return [
            'price'  => $resp->summary->income,
            'adult'  => $resp->summary->adult,
            'child'  => $resp->summary->child,
            'errors' => $errors,
        ];
    }

    public function addOrder($data)
    {
        $cData = [
            'excursion_id'  => $data['db_excursion_id'],
            'lang_id'       => $data['lang_id'],
            'adults'        => $data['adults'],
            'children'      => $data['children'],
            'date'          => $data['date'],
            'time'          => $data['time'],
            'client_name'   => $data['name'],
            'client_phone'  => $data['phone'],
            'client_email'  => $data['email'],
            'place_start'   => $data['place_start'],
        ];

        $sync = new Sync();
        $resp = $sync->load('add-order', $cData);

        var_dump($resp);


        return $resp->summary->income;
    }
}