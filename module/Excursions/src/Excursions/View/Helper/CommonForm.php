<?php
namespace Excursions\View\Helper;

use Aptero\Form\Form;
use Zend\View\Helper\AbstractHelper;

class CommonForm extends AbstractHelper
{
    public function __invoke(Form $form)
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
}