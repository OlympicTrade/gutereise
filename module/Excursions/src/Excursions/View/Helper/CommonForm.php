<?php
namespace Excursions\View\Helper;

use Aptero\Form\Form;
use Zend\View\Helper\AbstractHelper;

class CommonForm extends AbstractHelper
{
    public function __invoke(Form $form)
    {
        $view = $this->getView();
        /*$elLang     = $form->get('lang_id');
        $elDate     = $form->get('date');
        $elAdults   = $form->get('adults');
        $elChildren = $form->get('children');*/

        $html =
            '<div class="widget calc">'.
                '<div class="header">Калькулятор стоимости</div>'.
                $view->formElement($form->get('lang_id')).
                $view->formElement($form->get('excursion_id')).

                '<div class="row">'.
                    '<i class="far fa-calendar-alt"></i>'.
                    $view->formElement($form->get('date')).
                '</div>'.

                '<div class="row">'.
                    '<i class="fas fa-globe-americas"></i>'.
                    $view->formElement($form->get('time')).
                '</div>'.

                '<div class="row">'.
                    '<i class="fas fa-user"></i>'.
                    $view->formElement($form->get('adults')).
                '</div>'.

                '<div class="row">'.
                    '<i class="fas fa-child"></i>'.
                    $view->formElement($form->get('children')).
                '</div>'.

                /*'<div class="row cols">'.
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
                '</div>'.*/
            '</div>';

        return $html;
    }
}