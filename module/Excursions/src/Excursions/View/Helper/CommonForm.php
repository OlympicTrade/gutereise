<?php
namespace Excursions\View\Helper;

use Aptero\Form\Form;
use Zend\View\Helper\AbstractHelper;

class CommonForm extends AbstractHelper
{
    public function __invoke(Form $form, $fields)
    {
        $view = $this->getView();

        $html =
            '<div class="calc">'.
                '<div class="header">' . $view->tr('Рассчитать') . '</div>'.
                '<div class="body">';

        $html .=
            $view->formElement($form->get('id'));

        foreach ($fields as $field) {
            $html .=
                '<div class="row">'.
                    '<i class="' . $field['icon'] . '"></i>'.
                    $view->formElement($form->get($field['field'])).
                '</div>';
        }

        $html .=
                '</div>'.
            '</div>';

        return $html;
    }
}