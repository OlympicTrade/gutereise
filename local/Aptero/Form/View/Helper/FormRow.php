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
/*
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
}*/