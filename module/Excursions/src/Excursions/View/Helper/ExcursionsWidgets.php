<?php
namespace Excursions\View\Helper;

use Aptero\Form\Form;
use Zend\View\Helper\AbstractHelper;

class ExcursionsWidgets extends AbstractHelper
{
    public function __invoke($widget, $data = [])
    {
        switch ($widget) {
            case 'types':
                return $this->renderTypes($data);
                break;
            case 'search':
                return $this->renderSearch($data);
                break;
            case 'museums':
                return $this->renderMuseums($data);
                break;
            default:
                break;
        }

        return '';
    }

    protected function renderForm(Form $form)
    {
        $view = $this->getView();
        $elDate     = $form->get('date');
        $elLang     = $form->get('lang_id');
        $elAdults   = $form->get('adults');
        $elChildren = $form->get('children');

        $html =
            '<div class="widget calc">'.
                '<div class="header">' . $view->tr('Рассчитать стоимость') . '</div>'.
                '<div class="row cols">'.
                    '<div class="col-50">'.
                        '<div class="label">' . $view->tr($elDate->getLabel()) . '</div>'.
                        '<div class="element">'.
                            $view->formElement($elDate).
                        '</div>'.
                    '</div>'.
                    '<div class="col-50">'.
                        '<div class="label">' . $view->tr($elLang->getLabel()) . '</div>'.
                        '<div class="element">'.
                            $view->formElement($elLang).
                        '</div>'.
                    '</div>'.
                '</div>'.
                '<div class="row cols">'.
                    '<div class="col-50">'.
                        '<div class="label">' . $view->tr($elAdults->getLabel()) . '</div>'.
                        '<div class="element std-counter">'.
                            $view->formElement($elAdults).
                        '</div>'.
                    '</div>'.
                    '<div class="col-50">'.
                        '<div class="label">' . $view->tr($elChildren->getLabel()) . '</div>'.
                        '<div class="element std-counter">'.
                            $view->formElement($elChildren).
                        '</div>'.
                    '</div>'.
                '</div>'.
            '</div>';

        return $html;
    }

    protected function renderTypes($data)
    {
        $val = $data['value'];

        $view = $this->getView();

        $html =
            '<div class="widget types">'.
                '<div class="header">' . $view->tr('Тип экскурсии') . '</div>'.
                '<div class="body">'.
                    '<div class="links">'.
                        '<a href="/excursions/"' . (!$val ? ' class="active"' : '') . '>' . $view->tr('Все экскусрии') . '</a>';

        foreach ($data['data'] as $types) {
            $html .= '<a href="' . $types->getUrl() . '"' . ($val == $types->getId() ? ' class="active"' : '') . '>' . $view->tr($types->get('name')) . '</a>';
        }

        $html .=
                    '</div>'.
                '</div>'.
            '</div>';

        return $html;
    }

    protected function renderSearch($data)
    {
        $val = $data['value'];

        $view = $this->getView();

        $html =
            '<div class="widget search">'.
                '<i class="fas fa-search"></i>'.
                '<input type="text" name="search" value="' . $val . '" placeholder="' .  $view->tr('Найти экскурсию') . '">'.
            '</div>';

        return $html;
    }

    public function renderMuseums($data)
    {
        $html = '';

        $view = $this->getView();

        $i = 0;
        foreach ($data['data'] as $item) {
            $i++;

            if($i == 6) {
                $html .= '<div class="h-box">';
            }

            $checked = (isset($data['value']) && in_array($item->getId(), $data['value'])) ? ' checked' : '';

            $html .=
                '<label class="checkbox">'
                    .'<input type="checkbox" name="museums[]"' . $checked . ' value="' . $item->getId() . '"> ' . $view->tr($item->get('name'))
                .'</label>';
        }

        if($i > 5) {
            $html .=
                '</div>'
                .'<span class="show-all">' . $view->tr('показать все') . '</span>';
        } elseif($i <= 1) {
            return '';
        }

        $html =
            '<div class="widget museums">'
                .'<div class="header">' . $view->tr('Достопримечательности') . '</div>'
                .'<div class="body">' . $html . '</div>'
            .'</div>';

        return $html;
    }
}