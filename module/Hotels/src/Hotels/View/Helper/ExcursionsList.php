<?php
namespace Hotels\View\Helper;

use Aptero\Form\Form;
use Zend\Paginator\Paginator;
use Zend\View\Helper\AbstractHelper;

class HotelsList extends AbstractHelper
{
    public function __invoke($hotels, Form $form = null)
    {
        $v = $this->getView();

        $html =
           '';

        foreach ($hotels as $hotel) {
            $url = $hotel->getUrl();

            $hotel->checkDate();

            mb_strlen($v->tr($hotel->get('name')));

            $season = $hotel->checkDate();

            $html .=
                '<a href="' . $url . '" class="item">' .
                    '<div class="pic">' .
                        '<img src="' . $hotel->getPlugin('image')->getImage('m') . '" alt="' . $v->tr($hotel->get('name')) . '">' .
                    '</div>'.
                    '<div class="info">'.
                        (!$season['status'] ? '<div class="season">' . $season['header'] . '</div>' : '').
                        '<div class="name' . (mb_strlen($v->tr($hotel->get('name'))) >= 35 ? ' long' : '') . '"><span>' .
                            $v->tr($hotel->get('name')) .
                        '</span></div>'.
                        '<div class="desc">' .
                            $v->tr($hotel->get('preview')) .
                        '</div>'.
                    '</div>'.
                '</a>';
        }

        if($hotels instanceof Paginator) {
            $html .=
                $v->paginationControl($hotels, 'Sliding', 'pagination-slide-auto', ['route' => 'application/pagination']);
        }

        $html .=
            '';

        return $html;
    }
}