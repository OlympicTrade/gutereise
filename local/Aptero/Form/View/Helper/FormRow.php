<?php
namespace Aptero\Form\View\Helper;

use Zend\Form\Element;
use Zend\I18n\View\Helper\AbstractTranslatorHelper;

class FormRow extends AbstractTranslatorHelper
{
    public function __invoke(Element $element, $sizeType = 'small')
    {
        if (!$element) {
            return $this;
        }

        $html = '';

        $translator = $this->getTranslator();

        $label = $element->getLabel() ? $translator->translate($element->getLabel(), $this->getTranslatorTextDomain()) : '';

        $options = $element->getOptions();

        if (!empty($options['required'])) {
            $label .= ' <span class="required">*</span>';
        }

        $elType = $element->getAttribute('type');

        if (!$element->getAttribute('class')) {
            switch ($elType) {
                case 'text':
                    $element->setAttribute('class', 'std-input');
                    break;
                case 'number':
                    $element->setAttribute('class', 'std-input');
                    break;
                case 'tel':
                    $element->setAttribute('class', 'std-input');
                    break;
                case 'textarea':
                    $element->setAttribute('class', 'std-textarea');
                    break;
                case 'select':
                    $element->setAttribute('class', 'std-select');
                    break;
                default:
            }
        }

        if ($elType == 'checkbox') {
            $html .= $this->renderCheckbox($label, $element);
        } elseif($sizeType = 'full' && strpos('editor', $element->getAttribute('class')) === false) {
            $html .= $this->renderFull($label, $element);
        } else {
            $html .= $this->renderSmall($label, $element);
        }

        $html =
            '<div class="row">' . $html . '</div>';

        return $html;
    }

    protected function renderCheckbox($label, $element)
    {
        $html =
            '<label class="std-checkbox">'.
                $this->getView()->formElement($element).
                '<span>' . $label . '</span>'.
            '</label>';

        return $html;
    }

    protected function renderFull($label, $element)
    {
        $html =
            '<div class="element">'
                .'<div class="label">' . $label . '</div>'
                . $this->getView()->formElement($element) . $this->getView()->formElementErrors($element)
            .'</div>';

        return $html;
    }

    protected function renderSmall($label, $element)
    {
        $element->setAttribute('placeholder', $label);

        return $this->getView()->formElement($element) . $this->getView()->formElementErrors($element);
    }
}