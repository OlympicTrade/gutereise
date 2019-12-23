<?php
namespace Museums\View\Helper;

use Translator\Model\Translator;
use Zend\View\Helper\AbstractHelper;

class MobileMuseumsList extends AbstractHelper
{
    public function __invoke($museums)
    {
        $view = $this->getView();

        $html =
           '<div class="list">';

        foreach ($museums as $museum) {
            $url = $museum->getUrl();

            mb_strlen($view->tr($museum->get('name')));

            $html .=
                '<a href="' . $url . '" class="item">' .
                    '<div class="pic">' .
                        '<img src="' . $museum->getPlugin('image')->getImage('mm') . '" alt="' . $view->tr($museum->get('name')) . '">' .
                    '</div>'.
                    '<div class="info">'.
                        '<div class="name' . (mb_strlen($view->tr($museum->get('name'))) >= 35 ? ' long' : '') . '"><span>' .
                            $view->tr($museum->get('name')) .
                        '</span></div>'.
                    '</div>'.
                '</a>';
        }

        $html .=
            '</div>';

        return $html;
    }
}