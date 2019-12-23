<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Gallery extends AbstractHelper
{
    public function __invoke($model)
    {
        $html =
            '<div class="gallery flexslider">'.
                '<ul class="slides list">';

        $html .=
            '<li data-thumb="' . $model->getPlugin('image')->getImage('t') . '">'.
                '<img src="' . $model->getPlugin('image')->getImage('g') . '" alt="">'.
            '</li>';

        $i = 1;
        foreach ($model->getPlugin('images') as $image) {
            $i++;
            $html .=
                '<li data-thumb="' . $image->getImage('t') . '">'.
                    '<img src="' . $image->getImage('g') . '" alt=""></li>'.
                '</li>';
            if($i == 10) {
                break;
            }
        }

        $html .=
                '</ul>'.
            '</div>';

        return $html;
    }
}