<?php
namespace Application\View\Helper;

use Excursions\Model\Excursion;
use Zend\View\Helper\AbstractHelper;

class ShortList extends AbstractHelper
{
    public function __invoke($items, $options = [])
    {
        if(!$items->count()) {
            return '';
        }

        $view = $this->getView();

        $options = $options + [
            'header'  => '',
            'imgSize' => 'm',
            'desc'    => true,
        ];

        $slider = '';

        foreach ($items as $item) {
            $img = $item->getPlugin('image');

            $slider .=
                '<a href="' . $item->getUrl() . '" class="item">'.
                    '<div class="pic">'.
                        '<img src="' . $img->getImage($options['imgSize']) . '" alt="' . $img->getDesc() . '">'.
                    '</div>'.
                    '<div class="name">' . $item->get('name') . '</div>';

            if($item instanceof Excursion) {
                $slider .=
                    '<div class="price">от <span>' . $view->price($item->get('db_data')->price->rub->adult) . '</span> <i class="fal fa-ruble-sign"></i> / за 1 человека</div>';
            } else {
                $slider .=
                    '<div class="desc">' . $view->subStr($item->get('preview'), 120) . '</div>';
            }

            $slider .=
                '</a>';
        }

        if(!$slider) {
            return '';
        }

        $html =
            '<div class="short-list">';

        if($options['header']) {
            $html .=
                '<div class="std-header">'.
                    '<h2>' . $view->tr($options['header']) . '</h2>'.
                '</div>';
        }

        $html .=
                '<div class="nav">'.
                    '<div class="prev"></div>'.
                    '<div class="next"></div>'.
                '</div>'.
                '<div class="slider">'.
                    $slider.
                '</div>'.
            '</div>';

        return $html;
    }
}