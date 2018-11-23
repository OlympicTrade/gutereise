<?php
namespace Application\Model;

use Sync\Model\DbConstants;

class Language
{
    protected $langId = 1;

    static protected $instance;
    static public function getInstance()
    {
        if(!self::$instance) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        if($langId = $_COOKIE['lang_id'] && array_key_exists($_COOKIE['lang_id'], DbConstants::$languages)) {
            $this->langId = $_COOKIE['lang_id'];
        } else {
            $this->langId = 1;
        }
    }

    public function getLanguages()
    {
        return DbConstants::$languages;
    }

    public function getLangId()
    {
        return $this->langId;
    }

    public function setLangId($langId)
    {
        $this->langId = $langId;
        setcookie('lang_id', $langId, time()+(3600*365), '/');

        return $this;
    }
}