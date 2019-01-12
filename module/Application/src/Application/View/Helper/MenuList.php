<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MenuList extends AbstractHelper
{
    public function __invoke($items)
    {
        $html =
            '<ul>';

        $html .=
            '<li class="back"><span>Назад</span></li>';

        foreach ($items as $item) {
            $html .=
                '<li class="back"><a href="' . $item->getUrl() . '">' . $item->get('name') . '</a></li>';
        }

        $html .=
            '</ul>';

        return $html;
    }
}