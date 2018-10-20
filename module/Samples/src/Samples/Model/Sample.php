<?php
namespace Samples\Model;

use Aptero\Db\Entity\Entity;

class Sample extends Entity
{
    public function __construct()
    {
        $this->setTable('samples');

        $this->addProperties([
            'name'  => [],
        ]);

        $this->addPlugin('image', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('samples_images');
            $image->setFolder('samples');
            $image->addResolutions([
                'm' => [
                    'width'  => 250,
                    'height' => 250,
                    'crop'   => true,
                ],
            ]);

            return $image;
        });
    }
}