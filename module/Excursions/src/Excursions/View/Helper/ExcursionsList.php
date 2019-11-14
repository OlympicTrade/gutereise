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

            $html .=
                '<a href="' . $url . '" class="item">' .
                    '<div class="pic">' .
                        '<img src="' . $excursion->getPlugin('image')->getImage('m') . '" alt="' . $v->tr($excursion->get('name')) . '">' .
                    '</div>'.
                    '<div class="info">'.
                        '<div class="name"><span>' . $v->tr($excursion->get('name')) . '</span></div>'.
                        '<div class="desc">' . $v->tr($excursion->get('preview')) . '</div>'.
                    '</div>'.
                    /*'<div class="info">'.
                        '<div class="desc">' . $v->tr($excursion->get('preview')) . '</div>'.
                        '<div class="name"><div>' . $v->tr($excursion->get('name')) . '</div></div>'.
                        '<div class="price"><div>' .
                            'от <span>' . $v->price($excursion->getPrice()->adult) . '</span> <i class="fa fa-rub"></i>' .
                            '<div class="per">' . $v->tr('за человека') . '</div>'.
                        '</div></div>'.
                    '</div>'.*/
                '</a>';

                /*$html .=
                    '<div class="desc">'.
                        '<a  href="' . $url . '" class="name">' . $view->tr($excursion->get('name')) . '</a>'.
                        '<div class="preview">' . $view->tr($excursion->get('preview')) . '</div>'.
                    '</div>'.
                    '<div class="order">';

                $html .=
                        '<div class="price">'.
                            $view->tr('от') . ' <span>' . $view->price($excursion->getPrice()->adult) . '</span><br>'.
                            $view->tr('за человека').
                        '</div>'.
                        '<a href="' . $url . '" class="btn yellow">' . $view->tr('Узнать подробнее') . '</a>'.
                    '</div>'.
                '</div>';*/
        }

        if($excursions instanceof Paginator) {
            $html .=
                $v->paginationControl($excursions, 'Sliding', 'pagination-slide-auto', ['route' => 'application/pagination']);
        }

        $html .=
                //'<div class="clear"></div>'
            '';

        return $html;
    }
}