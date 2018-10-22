<?php
namespace Excursions\Model;

use Aptero\Db\Entity\Entity;
use Aptero\Db\Plugin\Images;
use Sync\Model\Sync;

class Excursion extends Entity
{
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
            'title'             => [],
            'description'       => [],
            'url'               => [],
        ]);

        $this->addPlugin('image', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('excursions_images');
            $image->setFolder('excursions');
            $image->addResolutions([
                'm' => [
                    'width'  => 450,
                    'height' => 230,
                    'crop'   => true,
                ],
                'r' => [
                    'width'  => 965,
                    'height' => 575,
                    'crop'   => true,
                ],
                'hr' => [
                    'width'  => 1366,
                    'height' => 658,
                    'crop'   => true,
                ]
            ]);

            return $image;
        });

        $this->addPlugin('images', function() {
            $image = new Images();
            $image->setTable('excursions_gallery');
            $image->setFolder('excursions_gallery');
            $image->addResolutions([
                'm' => [
                    'width'  => 400,
                    'height' => 300,
                    'crop'   => true,
                ],
                'r' => [
                    'width'  => 965,
                    'height' => 575,
                    'crop'   => true,
                ],
                'hr' => [
                    'width'  => 1366,
                    'height' => 658,
                    'crop'   => true,
                ]
            ]);

            $image->select()->order('sort');

            return $image;
        });

        $this->addPlugin('museums', function($model) {
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
        });
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