<?php
namespace Excursions\Model;

use Aptero\Db\Entity\Entity;
use Aptero\Db\Plugin\Images;
use Museums\Model\Museum;
use Sync\Model\Sync;

class Excursion extends Entity
{
    const NATIONALITY_ALL = 0;
    const NATIONALITY_RUS = 1; //Russians
    const NATIONALITY_FOR = 2; //Foreigners

    const TRANSPORT_AUTO = 1;
    const TRANSPORT_WALK = 2;

    public function __construct($options = [])
    {
        parent::__construct($options);

        $this->setTable('excursions');

        $this->addProperties([
            'db_excursion_id'   => [],
            'db_data'           => ['type' => 'json'],
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
                'g' => [
                    'width'  => 900,
                    'height' => 500,
                    'crop'   => true,
                ],
                'm' => [
                    'width'  => 500,
                    'height' => 320,
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

        /*$this->addPlugin('museums', function($model) {
            $item = new Entity();
            $item->setTable('excursions_museums');
            $item->addProperties([
                'depend'     => [],
                'museums_id' => [],
                'time'       => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
        });*/
    }

    public function getUrl()
    {
        return '/excursions/' . $this->get('url') . '/';
    }

    public function getPrice($params = [])
    {
        return $this->get('price');
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
        ];
    }
}