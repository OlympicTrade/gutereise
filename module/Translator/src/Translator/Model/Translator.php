<?php
namespace Translator\Model;

use Sync\Model\DbConstants;

class Translator
{
    protected $langCode = 'ru';

    protected $translator = null;

    static public $codes = [
        'ru'  => 1,
        'de'  => 2,
        'en'  => 3,
        'fr'  => 4,
        'sp'  => 5,
        'it'  => 6,
        'ch'  => 10,
    ];

    static public $codesTranscript = [
        'ru'  => 'Русский',
        'de'  => 'Немецкий',
        'en'  => 'Английский',
        'fr'  => 'Французкий',
        'sp'  => 'Испанский',
        'it'  => 'Итальянский',
        'ch'  => 'Китайский',
    ];

    protected $files = [
        'ru'  => 'ru',
        'de'  => 'de',
        'en'  => 'en',
        'fr'  => 'en',
        'sp'  => 'en',
        'it'  => 'en',
        'ch'  => 'en',
    ];

    protected $translatesPath  = MAIN_DIR . '/module/Translator/translates/';
    protected $translatesTable = [];

    static protected $instance;
    static public function getInstance()
    {
        if(!self::$instance) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct($langCode = null)
    {
        if($langCode) {
            $this->setLangCode($langCode);
        } else {
            $this->detectLanguage();
        }
    }

    protected function updateTranslateTable()
    {
        $file = $this->translatesPath . $this->files[$this->getLangCode()] . '.php';
        $this->translatesTable = include $file;
    }

    public function detectLanguage()
    {
        if($_COOKIE['lang'] && array_key_exists($_COOKIE['lang'], self::$codes)) {
            $this->setLangCode($_COOKIE['lang']);
            return $this;
        }

        $lang = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);

        if(in_array($lang, ['ru', 'ru_RU', 'ru_UA', 'bel', 'kk'])) {
            $langCode = 'ru';
        }

        if(in_array($lang, ['de', 'de_DE', 'de_CH'])) {
            $langCode = 'de';
        }

        if(!isset($langCode)) {
            $langCode = 'en';
        }

        $this->setLangCode($langCode);

        return $this;
    }

    public function isForeigners()
    {
        return $this->langCode != 'ru';
    }

    public function setLangCode($langCode = 'default')
    {
        if($langCode == 'default') {
            $langCode = 'ru';
        }

        $this->langCode = $langCode;

        $this->updateTranslateTable();
    }

    public function getLangCode()
    {
        return $this->langCode;
    }

    public function getLangId()
    {
        return /*DbConstants::$languages[*/self::$codes[$this->langCode]/*]*/;
    }

    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    public function getTranslator()
    {
        return $this->translator;
    }

    public function translate($str)
    {
        $code = \TranslatorAdmin\Model\Translator::getCode($str);

        if(!array_key_exists($code, $this->translatesTable) || !($translate = $this->translatesTable[$code])) {
            return $str;
        }

        if($translate == $code) {
            return $str;
        }

        return $translate;
    }

    public function getLanguages($translate = true)
    {
        if(!$translate) return DbConstants::$languages;

        $list = [];

        foreach (DbConstants::$languages as $key => $val) {
            $list[$key] = $this->translate($val);
        }

        return $list;
    }
}