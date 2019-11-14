<?php
namespace Excursions\View\Helper;

use Aptero\Form\Form;
use Zend\View\Helper\AbstractHelper;

class ExcursionsWidgets extends AbstractHelper
{
    public function __invoke($data, $widgets)
    {
        $html = '';

        $html .=
            '<div class="filters">' .
                '<input type="hidden" class="base-url" value="' . $data['url'] . '">';

        foreach ($widgets as $wName => $wData) {
            switch ($wName) {
                case 'search':
                    $html .= $this->renderSearch($wData);
                    break;
                case 'catalog':
                    $html .= $this->renderCatalog($wData);
                    break;
                default:
                    break;
            }
        }

        $html .= '</div>';

        return $html;
    }

    protected function renderSearch($data)
    {
        $val = $data['value'];

        $view = $this->getView();

        $html =
            '<div class="search">'.
                '<i class="fas fa-search"></i>'.
                '<input class="search-input" type="text" name="query" value="' . $val . '" placeholder="' .  $view->tr('Поиск по названию') . '">'.
            '</div>';

        return $html;
    }

    public function renderCatalog($data)
    {
        $html = '';

        $view = $this->getView();

        $i = 0;
        $count = 0;
        foreach ($data['data'] as $item) {
            $i++; $count += $item->get('count');

            if($i == 10) {
                $html .= '<div class="h-box">';
            }

            $html .=
                '<div class="row">'.
                    '<a href="' . $item->getUrl() .'">' . $view->tr($item->get('name')) . ' <span>' . $item->get('count') . '</span></a>' .
                '</div>';
        }

        if($i > 9) {
            $html .=
                '</div>'
                .'<span class="btn s show-all">' . $view->tr('Весь список') . '</span>';
        } elseif($i <= 1) {
            return '';
        }

        $html =
            '<div class="row">'.
                '<a href="/excursions/">Все экскурсии<span> ' . $count . '</span></a>' .
            '</div>'.
            $html;

        return $html;
    }
}