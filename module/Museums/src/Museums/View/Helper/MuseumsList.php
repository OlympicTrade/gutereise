<?php
namespace Museums\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MuseumsList extends AbstractHelper
{
    public function __invoke($museums)
    {
        $view = $this->getView();

        $html =
           '';

        foreach ($museums as $museum) {
            $html .=
                '<div class="item">'.
                    '<a class="pic" href="' . $museum->getUrl() . '">'.
                        '<img src="' . $museum->getPlugin('image')->getImage('m') . '" alt="' . $museum->get('name') . '">'.
                    '</a>'.
                    '<div class="desc">'.
                        '<div class="name">' . $museum->get('name') . '</div>'.
                        '<div class="preview">' . $museum->get('preview') . '</div>'.
                    '</div>'.
                '</div>';
        }

        $html .=
                '<div class="clear"></div>'
            .'';

        return $html;
    }
}