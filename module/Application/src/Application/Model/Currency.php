<?php
namespace Application\Model;

use Translator\Model\Translator;

class Currency
{
    static public $currencies = [
        'rub'  => 'RUB',
        'eur'  => 'EUR',
        'usd'  => 'USD',
    ];

    static protected $instance;
    static public function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected $currency;

    public function __construct($currency = null)
    {
        if($currency) {
            $this->setCurrency($currency);
        } else {
            $this->detectCurrency();
        }
    }

    public function detectCurrency()
    {
        if($_COOKIE['currency'] && array_key_exists($_COOKIE['currency'], self::$currencies)) {
            $this->setCurrency($_COOKIE['currency']);
            return $this;
        }

        $lang = Translator::getInstance()->getLangCode();

        switch ($lang) {
            case 'ru':
                $currency = 'rub';
                break;
            default:
                $currency = 'eur';
                break;
        }

        $this->setCurrency($currency);

        return $this;
    }

    public function setCurrency($currency = 'default')
    {
        if($currency == 'default') {
            $currency = 'rub';
        }

        $this->currency = $currency;

        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }
}