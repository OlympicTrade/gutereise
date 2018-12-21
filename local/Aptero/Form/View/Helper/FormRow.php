<?php
namespace Aptero\Form\View\Helper;

use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

class FormRow extends AbstractHelper
{
    public function __invoke(ElementInterface $element)
    {
        $view = $this->getView();

        if($element instanceof Textarea) {
            $element->setAttribute('class', 'std-textarea');
        }

        if($element instanceof Text) {
            $element->setAttribute('class', 'std-input');
        }

        if($element instanceof Select) {
            $element->setAttribute('class', 'std-select');
        }

        $html =
            '<div class="row">'.
                $view->formElement($element).
            '</div>';

        return $html;
    }
}