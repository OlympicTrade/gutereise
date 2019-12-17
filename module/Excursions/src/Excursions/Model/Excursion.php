<?php
namespace Excursions\Model;

use Application\Model\Currency;
use Aptero\Db\Entity\Entity;
use Aptero\Db\Plugin\Images;
use Aptero\Exception\Exception;
use Aptero\String\Date;
use Museums\Model\Museum;
use Sync\Model\Sync;

class Excursion extends Entity
{
    const NATIONALITY_ALL = 0;
    const NATIONALITY_RUS = 1; //Russians
    const NATIONALITY_FOR = 2; //Foreigners

    const TRANSPORT_AUTO = 1;
    const TRANSPORT_WALK = 2;

    const TYPE_EXCURSION = 0;
    const TYPE_TOUR      = 1;

    public function __construct($options = [])
    {
        parent::__construct($options);

        $this->setTable('excursions');

        $this->addProperties([
            'db_excursion_id'   => [],
            'db_data'           => ['type' => 'json'],
            'options'           => ['type' => 'json'],
            'type'              => [],
            'transport'         => [],
            'name'              => [],
            'header'            => [],
            'header_desc'       => [],
            'text'              => [],
            'preview'           => [],
            'place_and_time'    => [],
            'nationality'       => [],
            'title'             => [],
            'description'       => [],
            'url'               => [],
        ]);

        $this->addPlugin('reco', function($model) {
            $items = Excursion::getEntityCollection();
            $items->select()
                ->join(['er' => 'excursions_reco'], 'er.excursion_id = t.id', [])
                ->where(['er.depend' => $model->getId()]);

            return $items;
        });

        $this->addPlugin('pricetable', function($model) {
            $item = new Entity();
            $item->setTable('excursions_pricetable');
            $item->addProperties([
                'depend'     => [],
                'text'       => [],
            ]);

            $list = $item->getCollection();
            $list->select()->where(['depend' => $model->getId()]);

            return $list;
        });

        $this->addPlugin('plan', function($model) {
            $item = new Entity();
            $item->setTable('excursions_plan');
            $item->addProperties([
                'depend'     => [],
                'icon'       => [],
                'header'     => [],
                'text'       => [],
            ]);
            $catalog = $item->getCollection();
            $catalog->select()->where(['depend' => $model->getId()]);

            return $catalog;
        });

        $this->addPlugin('museums', function($model) {
            $items = Museum::getEntityCollection();
            $items->select()
                ->join(['em' => 'excursions_museums'], 'em.museum_id = t.id', [])
                ->where(['em.depend' => $model->getId()]);

            return $items;
        });

        $this->addPlugin('background', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('excursions_headers');
            $image->setFolder('excursions_headers');
            $image->addResolutions([
                'h' => [
                    'width'  => 1920,
                    'height' => 400,
                    'crop'   => true,
                ],
                's' => [
                    'width'  => 255,
                    'height' => 74,
                    'crop'   => true,
                ],
            ]);
            return $image;
        });

        $this->addPlugin('image', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('excursions_images');
            $image->setFolder('excursions');
            $image->addResolutions([
                't' => [
                    'width'  => 90,
                    'height' => 65,
                    'crop'   => true,
                ],
                'mm' => [
                    'width'  => 400,
                    'height' => 260,
                    'crop'   => true,
                ],
                'm' => [
                    'width'  => 400,
                    'height' => 360,
                    'crop'   => true,
                ],
                'g' => [
                    'width'  => 900,
                    'height' => 500,
                    'crop'   => true,
                ],
                'r' => [
                    'width'  => 965,
                    'height' => 575,
                    'crop'   => true,
                ],
                'hr' => [
                    'width'  => 1780,
                    'height' => 970,
                ]
            ]);

            return $image;
        });

        $this->addPlugin('images', function() {
            $image = new Images();
            $image->setTable('excursions_gallery');
            $image->setFolder('excursions_gallery');
            $image->addResolutions([
                't' => [
                    'width'  => 90,
                    'height' => 65,
                    'crop'   => true,
                ],
                'g' => [
                    'width'  => 900,
                    'height' => 500,
                    'crop'   => true,
                ],
                'r' => [
                    'width'  => 965,
                    'height' => 575,
                    'crop'   => true,
                ],
                'hr' => [
                    'width'  => 1780,
                    'height' => 970,
                ]
            ]);

            $image->select()->order('sort');

            return $image;
        });
    }

    public function checkDate()
    {
        $options = $this->get('options');
        if(empty($options->date)) {
            return ['status' => true];
        }

        try {
            list($from, $to) = explode('-', $options->date);
            $cDate = date('d.m');
            $result = $cDate > $from && $cDate < $to;

            if($result) {
                return ['status' => true];
            }

            return [
                'status'  => $result,
                'header'  => '<i class="fal fa-sun"></i> Летняя экскурсия',
                'desc' =>
                    'Данная экскурсия активна только в летний период с ' .
                    mb_strtolower((new Date(\DateTime::createFromFormat('d.m', $from)))->toStr(['year' => false])).
                    ' по '.
                    mb_strtolower((new Date(\DateTime::createFromFormat('d.m', $to)))->toStr(['year' => false]))
            ];
        } catch (Exception $e) {
            return ['status' => true];
        }
    }

    public function isTour()
    {
        return count($this->get('db_data')->days) > 1;
    }

    public function getUrl()
    {
        return '/excursions/' . $this->get('url') . '/';
    }

    public function getPrice($params = [])
    {
        return $this->get('db_data')->price->{Currency::getInstance()->getCurrency()};

        /*return $this->get('price');
        $sync = new Sync();

        $price = $sync->getExcursionPrice([
            'excursion_id'  => $this->get('db_excursion_id'),
            'adults'        => $params['adults'],
            'children'      => $params['children'],
            'lang_id'       => $params['lang_id'],
        ])->summary;

        return [
            'adult'    => $price->adult,
            'child'    => $price->child,
            'tourist'  => ($price->child ? ($price->child + $price->adult) / 2 : $price->adult),
            'total'    => $price->income,
        ];*/
    }
}