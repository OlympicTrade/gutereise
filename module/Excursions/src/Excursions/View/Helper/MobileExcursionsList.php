<?php
namespace Excursions\View\Helper;

use Translator\Model\Translator;
use Zend\View\Helper\AbstractHelper;

class MobileExcursionsList extends AbstractHelper
{
    public function __invoke($excursions)
    {
        $view = $this->getView();

        $html =
           '<div class="list">';

        foreach ($excursions as $excursion) {
            $url = $excursion->getUrl();

            $excursion->checkDate();

            mb_strlen($view->tr($excursion->get('name')));

            $season = $excursion->checkDate();

            $price = $excursion->get('db_data')->price->rub->adult;

            $html .=
                '<a href="' . $url . '" class="item">' .
                    '<div class="pic">' .
                        '<img src="' . $excursion->getPlugin('image')->getImage('mm') . '" alt="' . $view->tr($excursion->get('name')) . '">' .
                    '</div>'.
                    '<div class="info">'.
                        (!$season['status'] ? '<div class="season">' . $season['header'] . '</div>' : '').
                        '<div class="name' . (mb_strlen($view->tr($excursion->get('name'))) >= 35 ? ' long' : '') . '"><span>' .
                            $view->tr($excursion->get('name')) .
                        '</span></div>'.
                        '<div class="price">' .
                            'от ' . $view->price($price) . ' / за 1 человека' .
                        '</div>'.
                        /*'<div class="desc">' .
                            $v->tr($excursion->get('preview')) .
                        '</div>'.*/
                    '</div>'.
                '</a>';


            /*if(Translator::getInstance()->getLangCode() == 'ru') {
                $price = $excursion->get('db_data')->price->rub->adult;
            } else {
                $price = $excursion->get('db_data')->price->eur->adult;
            }

            $url = $excursion->getUrl();



            $html .=
                '<div class="item">'.
                    '<a href="' . $url . '" class="pic">'.
                        '<img src="' . $excursion->getPlugin('image')->getImage('m') . '" alt="' . $excursion->get('name') . '">'.
                    '</a>'.
                    '<a href="' . $url . '" class="name">' . $view->tr($excursion->get('name')) . '</a>'.
                    '<div class="preview">' . $view->tr($excursion->get('preview')) . '</div>'.
                    '<div class="info block">'.
                        '<div class="row">'.
                            '<i class="far fa-clock"></i> ' . $view->tr($view->declension($excursion->get('db_data')->duration, ['час', 'часа', 'часов'])).
                        '</div>'.
                        '<div class="row">'.
                            '<i class="far fa-user"></i> ' . $view->tr('от') . ' ' . $view->price($price).
                        '</div>'.
                    '</div>'.
                    '<span class="btn c2">' . $view->tr('Бронировать') . '</span>'.
                '</div>';*/
        }

        $html .=
            '</div>';

        return $html;
    }
}