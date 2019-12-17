<?php
namespace Hotels\View\Helper;

use Translator\Model\Translator;
use Zend\View\Helper\AbstractHelper;

class MobileHotelsList extends AbstractHelper
{
    public function __invoke($hotels)
    {
        $view = $this->getView();

        $html =
           '<div class="list">';

        foreach ($hotels as $hotel) {
            if(Translator::getInstance()->getLangCode() == 'ru') {
                $price = $hotel->get('db_data')->price->rub->adult;
            } else {
                $price = $hotel->get('db_data')->price->eur->adult;
            }

            $url = $hotel->getUrl();

            $html .=
                '<div class="item">'.
                    '<a href="' . $url . '" class="pic">'.
                        '<img src="' . $hotel->getPlugin('image')->getImage('m') . '" alt="' . $hotel->get('name') . '">'.
                    '</a>'.
                    '<a href="' . $url . '" class="name">' . $view->tr($hotel->get('name')) . '</a>'.
                    '<div class="preview">' . $view->tr($hotel->get('preview')) . '</div>'.
                    '<div class="info block">'.
                        '<div class="row">'.
                            '<i class="far fa-clock"></i> ' . $view->tr($view->declension($hotel->get('db_data')->duration, ['час', 'часа', 'часов'])).
                        '</div>'.
                        '<div class="row">'.
                            '<i class="far fa-user"></i> ' . $view->tr('от') . ' ' . $view->price($price).
                        '</div>'.
                    '</div>'.
                    '<span class="btn c2">' . $view->tr('Бронировать') . '</span>'.
                '</div>';
        }

        $html .=
            '</div>';

        return $html;
    }
}