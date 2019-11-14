<?php

namespace Excursions\Service;

use Translator\Model\Translator;
use Aptero\Service\AbstractService;
use Excursions\Model\Excursion;
use Excursions\Model\Tags;
use Sync\Model\DbConstants;
use Sync\Model\Sync;

class ToursService extends AbstractService
{
    public function getTag($filters = [])
    {
        $types = new Tags();

        if($filters['url']) {
            $types->select()->where(['url' => $filters['url']]);
        }

        return $types;
    }

    public function getPaginator($page, $filters = [], $itemsPerPage = 18)
    {
        $filters['join'] = [
            'image'
        ];

        $excursions = Excursion::getEntityCollection();
        $excursions->setSelect($this->getToursSelect($filters));

        return $excursions->getPaginator($page, $itemsPerPage);
    }

    public function getToursSelect($filters = [])
    {
        $select = $this->getSql()->select()
            ->from(['t' => 'excursions'])
            ->where(['type' => Excursion::TYPE_TOUR])
            ->group('t.id');

        if (in_array('image', $filters['join'])) {
            $select
                ->join(['ei' => 'excursions_images'], 't.id = ei.depend', ['image-id' => 'id', 'image-filename' => 'filename'], 'left');
        }

        if(!empty($filters['url'])) {
            $select->where(['t.url' => $filters['url']]);
        }

        if(!empty($filters['query'])) {
            $select->where->like('t.name',  '%' . $filters['query'] . '%');
        }

        if(!$filters['query'] && !empty($filters['tag'])) {
            $select
                ->join(['ett' => 'excursions_ett'], 't.id = ett.depend', [], 'left')
                ->where(['ett.tag_id' => $filters['tag']]);
        }

        $language = Translator::getInstance();
        if($language->isForeigners()) {
            $select->where
                ->in('nationality', [Excursion::NATIONALITY_ALL, Excursion::NATIONALITY_FOR]);
        } else {
            $select->where
                ->in('nationality', [Excursion::NATIONALITY_ALL, Excursion::NATIONALITY_RUS]);
        }

        return $select;
    }

    public function getTours($filters = [])
    {
        $excursions = Excursion::getEntityCollection();

        if($filters['url']) {
            $excursions->select()->where([
                'url' => $filters['url'],
                'type' => Excursion::TYPE_TOUR
            ]);
        }

        return $excursions;
    }

    public function getTour($filters = [])
    {
        $excursion = new Excursion();

        $excursion->select()
            ->where([
                'url' => $filters['url'],
                'type' => Excursion::TYPE_TOUR
            ]);

        return $excursion;
    }

    public function getPrice($excursion, $data)
    {
        if(!$data['date'] || !$data['adults']) {
            return ['errors' => ['Заполните форму для рассчета стоимости']];
        }

        $langs = Translator::getInstance();

        unset($data['id']);
        $data['excursion_id'] = $excursion->get('db_excursion_id');
        $data['lang_id'] = $langs->getLangId();

        $sync = new Sync();
        $resp = $sync->load('get-price', $data);

        $errors = (array) $resp->errors;
        foreach($resp->days as $day) {
            $errors += (array) $day->museums->errors;
            $errors += (array) $day->transports->errors;
            $errors += (array) $day->guides->errors;
        }

        foreach($errors as $code => &$error) {
            $error = DbConstants::$errors[$code];
        }

        if($langs->getLangCode() == 'ru') {
            return [
                'price'  => $resp->summary->income,
                'adult'  => $resp->summary->adult,
                'child'  => $resp->summary->child,
                'errors' => $errors,
            ];
        } else {
            foreach ($errors as $key => $error) {
                $errors[$key] = $langs->translate($error);
            }

            return [
                'price'  => $resp->summary->euro->income,
                'adult'  => $resp->summary->euro->adult,
                'child'  => $resp->summary->euro->child,
                'errors' => $errors,
            ];
        }
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

        dd($resp);

        return $resp->summary->income;
    }
}