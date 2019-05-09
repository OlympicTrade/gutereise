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
                        '<div class="name">' . $transport->get('name') . '</div>' .
                        '<div class="props">';

            $i = 0;
            foreach ($transport->getPlugin('props') as $row) {
                if($i > 4) break;
                $i++;

                $html .=
                    '<div class="row">' . $row->get('name') . '</div>';
            }

            $html .=
                        '</div>'.
                    '</div>'.
                '</div>';
        }

        $html .=
            '<div class="clear"></div>';

        return $html;
    }
}