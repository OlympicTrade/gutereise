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
            case 'museums':
                return $this->renderMuseums($data);
                break;
            default:
                break;
        }

        return '';
    }
    /*
    public function __invoke($form, $data)
    {
        $html =
           '<div class="sidebar filters">';

        //$html .= $this->renderForm($form);
        //$html .= $this->renderLinks('Тип экскурсии', $data['types']);
        $html .= $this->renderLinks('Тип экскурсии', 'types', $data['types']);
        $html .= $this->renderCheckbox('Достопримечательности', 'museums', $data['museums']);

        $html .=
            '</div>';

        return $html;
    }*/

    protected function renderForm(Form $form)
    {
        $view = $this->getView();
        $elDate     = $form->get('date');
        $elLang     = $form->get('lang_id');
        $elAdults   = $form->get('adults');
        $elChildren = $form->get('children');

        $html =
            '<div class="widget calc">'.
                '<div class="header">Рассчитать стоимость</div>'.
                '<div class="row cols">'.
                    '<div class="col-50">'.
                        '<div class="label">' . $elDate->getLabel() . '</div>'.
                        '<div class="element">'.
                            $view->formElement($elDate).
                        '</div>'.
                    '</div>'.
                    '<div class="col-50">'.
                        '<div class="label">' . $elLang->getLabel() . '</div>'.
                        '<div class="element">'.
                            $view->formElement($elLang).
                        '</div>'.
                    '</div>'.
                '</div>'.
                '<div class="row cols">'.
                    '<div class="col-50">'.
                        '<div class="label">' . $elAdults->getLabel() . '</div>'.
                        '<div class="element std-counter">'.
                            $view->formElement($elAdults).
                        '</div>'.
                    '</div>'.
                    '<div class="col-50">'.
                        '<div class="label">' . $elChildren->getLabel() . '</div>'.
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

        $html =
            '<div class="widget types">'.
                '<div class="header">Тип экскурсии</div>'.
                '<div class="body">'.
                    '<div class="links">'.
                        '<a href="/excursions/"' . (!$val ? ' class="active"' : '') . '>Все экскусрии</a>';

        foreach ($data['data'] as $types) {
            $html .= '<a href="' . $types->getUrl() . '"' . ($val == $types->getId() ? ' class="active"' : '') . '>' . $types->get('name') . '</a>';
        }

        $html .=
                    '</div>'.
                '</div>'.
            '</div>';

        return $html;
    }

    public function renderMuseums($data)
    {
        $html = '';

        $i = 0;
        foreach ($data['data'] as $item) {
            $i++;

            if($i == 6) {
                $html .= '<div class="h-box">';
            }

            $checked = (isset($data['value']) && in_array($item->getId(), $data['value'])) ? ' checked' : '';

            $html .=
                '<label class="checkbox">'
                    .'<input type="checkbox" name="museums[]"' . $checked . ' value="' . $item->getId() . '"> ' . $item->get('name')
                .'</label>';
        }

        if($i > 5) {
            $html .=
                '</div>'
                .'<span class="show-all">показать все</span>';
        } elseif($i <= 1) {
            return '';
        }

        $html =
            '<div class="widget museums">'
                .'<div class="header">Достопримечательности</div>'
                .'<div class="body">' . $html . '</div>'
            .'</div>';

        return $html;
    }
}