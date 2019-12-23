<?php
namespace Transports\View\Helper;

use Translator\Model\Translator;
use Zend\View\Helper\AbstractHelper;

class MobileTransportsList extends AbstractHelper
{
    public function __invoke($transports)
    {
        $view = $this->getView();

        $html = '';

        foreach ($transports as $transport) {
            $html .=
                '<div class="item">'.
                    '<a class="pic" href="' . $transport->getUrl() . '">'.
                        '<img src="' . $transport->getPlugin('image')->getImage('m') . '" alt="' . $transport->get('name') . '">'.
                    '</a>'.
                    '<div class="info">'.
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


        return $html;
    }
}