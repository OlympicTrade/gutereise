<?php
namespace Transports\View\Helper;

use Zend\View\Helper\AbstractHelper;

class TransportsList extends AbstractHelper
{
    public function __invoke($transports)
    {
        $view = $this->getView();

        $html =
            '';

        foreach ($transports as $transport) {
            $html .=
                '<div class="item">'.
                    '<a class="pic" href="' . $transport->getUrl() . '">'.
                        '<img src="' . $transport->getPlugin('image')->getImage('m') . '" alt="' . $transport->get('name') . '">'.
                    '</a>'.
                    '<div class="desc">'.
                        '<div class="name">' . $transport->get('name') . '</div>'.
                        '<div class="preview">' . $transport->get('preview') . '</div>'.
                        '<div class="btns">' .
                            '<div class="btn gray">Отложить</div>'.
                            '<div class="btn yellow">Бронировать</div>'.
                        '</div>'.
                    '</div>'.
                '</div>';
        }

        $html .=
            '<div class="clear"></div>';

        return $html;
    }
}