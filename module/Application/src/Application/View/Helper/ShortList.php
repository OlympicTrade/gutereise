<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ShortList extends AbstractHelper
{
    public function __invoke($items, $options = [])
    {
        $options = [
            'header'  => '',
            'imgSize' => 'm',
        ] + $options;

        $html = '';

        if($options['header']) {
            $html .=
                '<div class="std-header">'.
                    '<h2>' . $this->tr('Достопримечательности') . '</h2>'.
                    '<div class="separ"></div>'.
                '</div>';
        }

        foreach ($items as $item) {
            $img = $item->getPlugin('image');
            $html .=
                '<a href="' . $item->getUrl() . '" class="item">'.
                    '<div class="pic">'.
                        '<img src="' . $img->getImage($options['imgSize']) . '" alt="' . $img->getDesc() . '">'.
                    '</div>'.
                    '<div class="name">' . $item->get('name') . '</div>'.
                    '<div class="desc">' . $item->get('preview') . '</div>'.
                '</a>';
        }

        if(!$html) {
            return '';
        }

        $html =
            '<div class="short-list">'.
                '<div class="nav">'.
                    '<div class="prev"></div>'.
                    '<div class="next"></div>'.
                '</div>'.
                '<div class="slider">'.
                    $html.
                '</div>'.
            '</div>';

        return $html;
    }
}