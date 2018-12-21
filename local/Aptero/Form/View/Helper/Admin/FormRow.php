<?php
namespace Aptero\Form\View\Helper\Admin;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

class FormRow extends AbstractHelper
{
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        $html =
            '<div class="row">';

        $label = $element->getLabel();

        $options = $element->getOptions();

        if(!empty($options['required'])) {
            $label .= ' <span class="asterisk">*</span>';
        }

        $html .=
            '<span class="label">' . $label . '</span>';

        $html .=
            $this->getView()->formElement($element);

        if(!empty($options['help'])) {
            $html .=
                '<span class="tooltip">'
                    . '<div class="tooltip">'
                    . '<div class="tooltip-icon"><i class="fa fa-question-circle"></i></div>'
                        . '<div class="tooltip-desc">'
                        . $options['help']
                        . '</div>'
                    . '</div>'
                . '</span>';
        }

        $html .=
            '</div>';

        return $html;
    }
}