<?php
namespace Excursions\View\Helper;

use Aptero\Form\Form;
use Zend\Paginator\Paginator;
use Zend\View\Helper\AbstractHelper;

class ExcursionsList extends AbstractHelper
{
    public function __invoke($excursions, Form $form = null)
    {
        $v = $this->getView();

        $html =
           '';

        foreach ($excursions as $excursion) {
            $url = $excursion->getUrl();

            $excursion->checkDate();

            mb_strlen($v->tr($excursion->get('name')));

            $season = $excursion->checkDate();

            $html .=
                '<a href="' . $url . '" class="item">' .
                    '<div class="pic">' .
                        '<img src="' . $excursion->getPlugin('image')->getImage('m') . '" alt="' . $v->tr($excursion->get('name')) . '">' .
                    '</div>'.
                    '<div class="info">'.
                        (!$season['status'] ? '<div class="season">' . $season['header'] . '</div>' : '').
                        '<div class="name' . (mb_strlen($v->tr($excursion->get('name'))) >= 35 ? ' long' : '') . '"><span>' .
                            $v->tr($excursion->get('name')) .
                        '</span></div>'.
                        '<div class="desc">' .
                            $v->tr($excursion->get('preview')) .
                        '</div>'.
                    '</div>'.
                '</a>';
        }

        if($excursions instanceof Paginator) {
            $html .=
                $v->paginationControl($excursions, 'Sliding', 'pagination-slide-auto', ['route' => 'application/pagination']);
        }

        $html .=
            '';

        return $html;
    }
}