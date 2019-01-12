<?php
namespace Transports\View\Helper;

use Translator\Model\Translator;
use Zend\View\Helper\AbstractHelper;

class MobileTransportsList extends AbstractHelper
{
    public function __invoke($excursions)
    {
        $view = $this->getView();

        $html =
           '<div class="list">';

        foreach ($excursions as $excursion) {
            /*if(Translator::getInstance()->getLangCode() == 'ru') {
                $price = $excursion->get('db_data')->price->rub->adult;
            } else {
                $price = $excursion->get('db_data')->price->eur->adult;
            }*/

            $url = $excursion->getUrl();

            $html .=
                '<div class="item">'.
                    '<a href="' . $url . '" class="pic">'.
                        '<img src="' . $excursion->getPlugin('image')->getImage('m') . '" alt="' . $excursion->get('name') . '">'.
                    '</a>'.
                    '<a href="' . $url . '" class="name">' . $view->tr($excursion->get('name')) . '</a>'.
                    '<div class="preview">' . $view->tr($excursion->get('preview')) . '</div>'.
                    '<div class="info block">'.
                        /*'<div class="row">'.
                            '<i class="far fa-clock"></i> ' . $view->tr($view->declension($excursion->get('db_data')->duration, ['час', 'часа', 'часов'])).
                        '</div>'.
                        '<div class="row">'.
                            '<i class="far fa-user"></i> ' . $view->tr('от') . ' ' . $view->price($price).
                        '</div>'.*/
                    '</div>'.
                    '<span class="btn c2">' . $view->tr('Бронировать') . '</span>'.
                '</div>';
        }

        $html .=
            '</div>';

        return $html;
    }
}