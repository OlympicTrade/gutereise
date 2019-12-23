<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MenuList extends AbstractHelper
{
    public function __invoke($items)
    {
        $view = $this->getView();

        $html =
            '<ul>'.
                '<li class="back"><span>' . $view->tr('Назад') . '</span></li>'.
                '<li><a href="/excursions/">' . $view->tr('Все экскурсии') . '</a></li>';

        foreach ($items as $item) {
            $html .=
                '<li><a href="' . $item->getUrl() . '">' . $view->tr($item->get('name')) . '</a></li>';
        }

        $html .=
            '</ul>';

        return $html;
    }
}