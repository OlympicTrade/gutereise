<?php
namespace Application\Model;

use ApplicationAdmin\Model\Plugin\ContentImages;
use Aptero\Db\Entity\Entity;

class Content extends Entity
{
    public function __construct()
    {
        $this->setTable('content');

        $this->addProperties([
            'depend'      => [],
            'module'      => [],
            'text'        => [],
            'sort'        => [],
        ]);

        $this->addPlugin('attrs', function() {
            $properties = new \Aptero\Db\Plugin\Attributes();
            $properties->setTable('content_attrs');

            return $properties;
        });

        $this->addPlugin('images', function() {
            $image = new ContentImages();
            $image->setTable('content_gallery');
            $image->setFolder('content');
            $image->addResolutions([
                'm' => [
                    'width'  => 900,
                    'height' => 500,
                    'crop'   => true,
                ],
                'p' => [
                    'width'  => 900,
                    'height' => 3000,
                ],
                'hr' => array(
                    'width'  => 1920,
                    'height' => 1150,
                )
            ]);

            return $image;
        });
    }
}