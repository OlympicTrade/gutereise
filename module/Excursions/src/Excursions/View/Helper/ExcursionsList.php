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
                        '<img src="' . $excursion->getPlugin('image')->getImage('m') . '" alt="' . $excursion->get('name') . '">' .
                    '</a>';

            //if ($form) {
                /*$params = [
                    'date' => $form->get('date')->getValue(),
                    'lang_id' => $form->get('lang_id')->getValue(),
                    'adults' => $form->get('adults')->getValue(),
                    'children' => $form->get('children')->getValue(),
                ];*/

                $price = $excursion->getPrice();
                $html .=
                    '<div class="info">' .
                        '<div class="row">' .
                            'Длительность <i class="far fa-clock"></i> ' . $view->declension($excursion->get('duration'), ['час', 'часа', 'часов']) .
                        '</div>' .
                        /*'<div class="row">' .
                            '<i class="fas fa-ruble-sign"></i> ' . $view->price($price) . ' руб. (' . $view->price($price) . ' c чел.)' .
                        '</div>' .*/
                    '</div>';
                //}

                $html .=
                    '<div class="desc">'.
                        '<a  href="' . $excursion->getUrl() . '" class="name">' . $excursion->get('name') . '</a>'.
                        '<div class="preview">' . $excursion->get('preview') . '</div>'.
                        '<div class="btns">' .
                            '<div class="btn gray">Отложить</div>'.
                            '<div class="btn yellow">Бронировать</div>'.
                        '</div>'.
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