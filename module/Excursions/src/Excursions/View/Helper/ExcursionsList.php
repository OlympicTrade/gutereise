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

            $excursion->checkDate();

            mb_strlen($view->tr($excursion->get('name')));

            $season = $excursion->checkDate();

            $price = $excursion->get('db_data')->price->rub->adult;

            $html .=
                '<a href="' . $url . '" class="item">' .
                    '<div class="pic">' .
                        '<img src="' . $excursion->getPlugin('image')->getImage('m') . '" alt="' . $view->tr($excursion->get('name')) . '">' .
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
        }

        if($excursions instanceof Paginator) {
            $html .=
                $view->paginationControl($excursions, 'Sliding', 'pagination-slide-auto', ['route' => 'application/pagination']);
        }

        $html .=
            '';

        return $html;
    }
}