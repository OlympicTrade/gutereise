<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Gallery extends AbstractHelper
{
    public function __invoke($model)
    {
        $html =
            '<div class="gallery">'.
                '<ul class="list">';

        $html .=
            '<li data-thumb="' . $model->getPlugin('image')->getImage('hr') . '">'.
                '<img src="' . $model->getPlugin('image')->getImage('g') . '" alt="">'.
            '</li>';

        foreach ($model->getPlugin('images') as $image) {
            $html .=
                '<li data-thumb="' . $image->getImage('hr') . '">'.
                    '<img src="' . $image->getImage('g') . '" alt=""></li>'.
                '</li>';
        }

        $html .=
                '</ul>'.
            '</div>';

        return $html;
    }
}