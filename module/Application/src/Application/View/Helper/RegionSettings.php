<?php
namespace Application\View\Helper;

use Application\Model\Currency;
use Translator\Model\Translator;
use Zend\Form\Element\Select;
use Zend\View\Helper\AbstractHelper;

class RegionSettings extends AbstractHelper
{
    public function __invoke()
    {
        $view = $this->getView();

        $html =
            '<div class="settings">'.
                '<div class="row language">'.
                    //'<div class="label">' . $view->tr('Язык') . ':</div>'.
                    $this->languageSelect().
                '</div>'.
                '<div class="row sep">/</div>'.
                '<div class="row currency">'.
                    //'<div class="label">' . $view->tr('Валюта') . ':</div>'.
                    $this->currencySelect().
                '</div>'.
            '</div>';

        return $html;
    }

    public function currencySelect()
    {
        $element = new Select('_', [
            'options' => Currency::$currencies
        ]);
        $element->setValue(Currency::getInstance()->getCurrency());

        return $this->getView()->formElement($element);
    }

    public function languageSelect()
    {
        $language = Translator::getInstance();
        $langCode = $language->getLangCode();

        $html =
            '<div class="flags">';

        $langs = ['ru' => 'ru', 'en' => 'en', 'de' => 'de'];
        unset($langs[$langCode]);

        array_unshift($langs , $langCode);

        $first = true;
        foreach ($langs as $lang) {
            $html .=
                '<div class="flag ' . $lang . '' . ($first ? ' active' : '') . '" data-lang="' . $lang . '"></div>';

            $first = false;
        }

        $html .=
            '</div>';

        return $html;
    }
}