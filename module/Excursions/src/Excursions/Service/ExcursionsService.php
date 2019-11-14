<?php

namespace Excursions\Service;

use Application\Model\Currency;
use Translator\Model\Translator;
use Aptero\Service\AbstractService;
use Excursions\Model\Excursion;
use Excursions\Model\Tags;
use Sync\Model\DbConstants;
use Sync\Model\Sync;

class ExcursionsService extends AbstractService
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
        $excursions->setSelect($this->getExcursionsSelect($filters));

        return $excursions->getPaginator($page, $itemsPerPage);
    }

    public function getExcursionsSelect($filters = [])
    {
        $select = $this->getSql()->select()
            ->from(['t' => 'excursions'])
            ->where(['type' => Excursion::TYPE_EXCURSION])
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

    public function getPrice($excursion, $data)
    {
        if(!$data['date'] || !$data['adults']) {
            return ['errors' => ['Заполните форму для рассчета стоимости']];
        }

        $lang = Translator::getInstance();
        $currency = Currency::getInstance()->getCurrency();

        unset($data['id']);
        $data['excursion_id'] = $excursion->get('db_excursion_id');
        $data['currency'] = $currency;

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

        if($lang != 'ru') {
            foreach ($errors as $key => $error) {
                $errors[$key] = $lang->translate($error);
            }
        }

        $result = [
            'errors' => $errors,
            'price'  => $resp->summary->$currency->income,
            'adult'  => $resp->summary->$currency->adult,
            'child'  => $resp->summary->$currency->child,
        ];

        return $result;
    }

    public function addOrder($excursion, $data)
    {
        $cData = [
            'excursion_id'  => $excursion->get('db_excursion_id'),
            'lang_id'       => $data['lang_id'],
            'currency'      => Currency::getInstance()->getCurrency(),
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

        return $resp->summary->income;
    }
}