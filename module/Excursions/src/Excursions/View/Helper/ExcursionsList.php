<?php
namespace Excursions\View\Helper;

use Aptero\Form\Form;
use Zend\Paginator\Paginator;
use Zend\View\Helper\AbstractHelper;

class ExcursionsList extends AbstractHelper
{
    public function __invoke($excursions, Form $form = null)
    {
        $view = $this->getView();

        $html =
           '';

        foreach ($excursions as $excursion) {
            $url = $excursion->getUrl();

            $html .=
                '<div class="item">' .
                    '<a class="pic" href="' . $url . '">' .
                        '<img src="' . $excursion->getPlugin('image')->getImage('m') . '" alt="' . $view->tr($excursion->get('name')) . '">' .
                    '</a>';

            //if ($form) {
                /*$params = [
                    'date' => $form->get('date')->getValue(),
                    'lang_id' => $form->get('lang_id')->getValue(),
                    'adults' => $form->get('adults')->getValue(),
                    'children' => $form->get('children')->getValue(),
                ];*/

                $price = $excursion->getPrice();
                /*$html .=
                    '<div class="info">' .
                        '<div class="row">' .
                            'Длительность <i class="far fa-clock"></i> ' . $view->declension($excursion->get('duration'), ['час', 'часа', 'часов']) .
                        '</div>' .*/
                        /*'<div class="row">' .
                            '<i class="fas fa-ruble-sign"></i> ' . $view->price($price) . ' руб. (' . $view->price($price) . ' c чел.)' .
                        '</div>' .*/
                    //'</div>';
                //}

                $html .=
                    '<div class="desc">'.
                        '<a  href="' . $url . '" class="name">' . $view->tr($excursion->get('name')) . '</a>'.
                        //'<div class="time"><i class="far fa-clock"></i> ' . $view->tr($view->declension($excursion->get('duration'), ['час', 'часа', 'часов'])) . '</div>'.
                        '<div class="preview">' . $view->tr($excursion->get('preview')) . '</div>'.
                    '</div>'.
                    '<div class="order">';

                /*if($dt = Time::getDT($excursion->get('db_data')->days[0]->duration)) {
                    $html .=
                        '<div class="duration">'.
                            $view->tr('Длительность') . '<br>'.
                            '<div><i class="far fa-clock"></i> ' . $view->declension($dt->format('H'), ['час', 'часа', 'часов']) . '</div>'.
                        '</div>';
                }*/

                $html .=
                        '<div class="price">'.
                            $view->tr('от') . ' <span>' . $view->price($excursion->get('db_data')->price->rub->adult) . '</span><br>'.
                            $view->tr('за человека').
                        '</div>'.
                        '<a href="' . $url . '" class="btn yellow">' . $view->tr('Узнать подробнее') . '</a>'.
                    '</div>'.
                '</div>';
        }

        if($excursions instanceof Paginator) {
            $html .=
                $view->paginationControl($excursions, 'Sliding', 'pagination-slide-auto', ['route' => 'application/pagination']);
        }

        $html .=
                //'<div class="clear"></div>'
            '';

        return $html;
    }
}