<?php
namespace Museums\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MuseumsPointsList extends AbstractHelper
{
    public function __invoke($points)
    {
        $view = $this->getView();

        $html =
           '';

        foreach ($points as $point) {
            $html .=
                '<div class="item">'.
                    '<a class="pic" href="' . $point->getUrl() . '">'.
                        '<img src="' . $point->getPlugin('image')->getImage('m') . '" alt="' . $point->get('name') . '">'.
                    '</a>'.
                    '<div class="desc">'.
                        '<div class="name">' . $point->get('name') . '</div>'.
                        '<div class="preview">' . $view->substr($point->get('preview'), 120) . '</div>'.
                        '<div class="btns">' .
                            '<div class="btn gray">Отложить</div>'.
                            '<div class="btn yellow">Бронировать</div>'.
                        '</div>'.
                    '</div>'.
                '</div>';
        }

        $html .=
                '<div class="clear"></div>'
            .'';

        return $html;
    }
}