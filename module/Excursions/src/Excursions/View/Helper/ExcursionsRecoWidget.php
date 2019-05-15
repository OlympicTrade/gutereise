<?php
namespace Excursions\View\Helper;

use Aptero\DateTime\Time;
use Zend\View\Helper\AbstractHelper;

class ExcursionsRecoWidget extends AbstractHelper
{
    public function __invoke($items, $options = [])
    {
        $html = '';

        $options = $options + [
            'header' => 'Похожие экскурсии'
        ];

        $html .=
            '<div class="widget reco">'.
                '<div class="header">' . $this->getView()->tr($options['header']) . '</div>'.
                '<div class="body">';

        foreach ($items as $item) {
            $html .=
                '<a href="' . $item->getUrl() . '" class="item">'.
                    '<div class="pic"><img src="' . $item->getPlugin('background')->getImage('s') . '" alt="' . $item->get('name') . '"></div>'.
                    '<div class="name">' . $item->get('name') . '</div>'.
                    '<div class="info">';

            if($t = Time::getDT($item->get('db_data')->duration)) {
                $html .=
                    '<span class="duration"><i class="far fa-clock"></i> ' . $t->getString() . '</span>';
            }

            $html .=
                        '<span class="readmore">Узнать подробнее</span>'.
                    '</div>'.
                '</a>';
        }

        $html .=
                '</div>'.
            '</div>';

        return $html;
    }
}