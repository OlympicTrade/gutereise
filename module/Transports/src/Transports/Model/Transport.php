<?php
namespace Transports\Model;

use Aptero\Db\Entity\Entity;
use Aptero\Db\Plugin\Image;
use Aptero\Db\Plugin\Images;

class Transport extends Entity
{
    public function __construct()
    {
        $this->setTable('transports');

        $this->addProperties([
            'name'         => [],
            'preview'      => [],
            'text'         => [],
            'type'         => [],
            'title'        => [],
            'description'  => [],
            'url'          => [],
        ]);

        $this->addPlugin('image', function() {
            $image = new Image();
            $image->setTable('transports_images');
            $image->setFolder('transports');
            $image->addResolutions([
                'm' => [
                    'width'  => 600,
                    'height' => 400,
                    'crop'   => true,
                ],
            ]);

            return $image;
        });

        $this->addPlugin('images', function() {
            $image = new Images();
            $image->setTable('transports_gallery');
            $image->setFolder('transports_gallery');
            $image->addResolutions([
                'm' => [
                    'width'  => 600,
                    'height' => 400,
                    'crop'   => true,
                ],
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