<?php
namespace ApplicationAdmin\Model;

use Aptero\Db\Entity\Entity;

class About extends Entity
{
    public function __construct()
    {
        $this->setTable('about');

        $this->addProperties([
            'name'      => [],
            'text'      => [],
        ]);

        $this->addPlugin('certificate', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('about_certificate');
            $image->setFolder('about_certificate');
            $image->addResolutions([
                'a' => [
                    'width'  => 162,
                    'height' => 162,
                    'crop'   => true,
                ],
                'hr' => [
                    'width'  => 1000,
                    'height' => 800,
                ]
            ]);

            return $image;
        });
    }
}