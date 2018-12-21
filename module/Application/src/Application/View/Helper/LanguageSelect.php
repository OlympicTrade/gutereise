<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class LanguageSelect extends AbstractHelper
{
    public function __invoke()
    {
        $language = \Translator\Model\Translator::getInstance();
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