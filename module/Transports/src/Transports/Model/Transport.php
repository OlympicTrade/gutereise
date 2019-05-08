<?php
namespace Transports\Model;

use Aptero\Db\Entity\Entity;
use Aptero\Db\Plugin\Image;
use Aptero\Db\Plugin\Images;

class Transport extends Entity
{
    const TYPE_AUTO   = 1;
    const TYPE_BOAT   = 2;
    const TYPE_COPTER = 3;

    public function __construct()
    {
        $this->setTable('transports');

        $this->addProperties([
            'name'         => [],
            'preview'      => [],
            'text'         => [],
            'type'         => [],
            'capacity'     => [],
            'price'        => ['type' => 'json'],
            'title'        => [],
            'description'  => [],
            'url'          => [],
        ]);

        $this->addPlugin('props', function($model) {
            $item = new Entity();
            $item->setTable('transports_props');
            $item->addProperties([
                'depend'     => [],
                'name'       => [],
                'icon'       => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
        });

        $this->addPlugin('image', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('transports_images');
            $image->setFolder('transports');
            $image->addResolutions([
                'g' => [
                    'width'  => 900,
                    'height' => 500,
                    'crop'   => true,
                ],
                'm' => [
                    'width'  => 400,
                    'height' => 222,
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
            $image->setTable('transports_gallery');
            $image->setFolder('transports_gallery');
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
    }

    public function getUrl()
    {
        return '/transport/' . $this->get('url') . '/';
    }
}