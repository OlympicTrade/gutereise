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

            $html .=
                '<div class="item">' .
                    '<a class="pic" href="' . $excursion->getUrl() . '">' .
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
                        '<a  href="' . $excursion->getUrl() . '" class="name">' . $view->tr($excursion->get('name')) . '</a>'.
                        //'<div class="time"><i class="far fa-clock"></i> ' . $view->tr($view->declension($excursion->get('duration'), ['час', 'часа', 'часов'])) . '</div>'.
                        '<div class="preview">' . $view->tr($excursion->get('preview')) . '</div>'.
                    '</div>'.
                    '<div class="order">' .
                        '<div class="price">'.
                            $view->tr('от') . ' <span>15 000 <i class="fas fa-ruble-sign"></i></span> <br>'.
                            $view->tr('за человека').
                        '</div>'.
                        '<div class="btn yellow">' . $view->tr('Бронировать') . '</div>'.
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