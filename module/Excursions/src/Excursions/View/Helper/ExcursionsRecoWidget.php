<?php
namespace Excursions\View\Helper;

use Aptero\DateTime\Time;
use Zend\View\Helper\AbstractHelper;

class ExcursionsRecoWidget extends AbstractHelper
{
    public function __invoke($items, $options = [])
    {
        $view = $this->getView();
        $html = '';

        $options = $options + [
            'header' => 'Похожие экскурсии'
        ];

        foreach ($items as $item) {
            $rowHtml = '';

            $rowHtml .=
                '<a href="' . $item->getUrl() . '" class="item">'.
                    '<div class="name">' . $item->get('name') . '</div>'.
                    '<div class="info">'
                        //'<div class="pic"><img src="' . $item->getPlugin('image')->getImage('t') . '" alt="' . $item->get('name') . '"></div>'
            ;

            if($t = Time::getDT($item->get('db_data')->duration)) {
                $price = $item->get('db_data')->price->rub->adult;
                $rowHtml .=
                    '<div class="row duration">Длительность ' . $t->getString() . '</div>'.
                    '<div class="row price">от ' . $view->price($price) . ' / за человека</div>';
            } else {
                continue;
            }

            $rowHtml .=
                        '<span class="readmore">Узнать подробнее</span>'.
                    '</div>'.
                '</a>';

            $html .= $rowHtml;
        }

        if(!$html) {
            return '';
        }

        return
            '<div class="widget reco">'.
                '<div class="header">' . $this->getView()->tr($options['header']) . '</div>'.
                '<div class="body">'.
                    $html.
                '</div>'.
            '</div>';
    }
}