<?php
namespace Aptero\Form;

use Translator\Model\Translator;
use Zend\Form\Form as ZendForm;
use Aptero\Db\Entity\Entity;

class Form extends ZendForm {
    protected $translate = true;

    /**
     * @var Entity
     */
    protected $model = null;

    /**
     * @param Entity $model
     */
    public function setModel($model) {
        $this->model = $model;
    }

    public function isValid()
    {
        $this->setFilters();
        return parent::isValid();
    }

    /**
     * @return Entity
     */
    public function getModel()
    {
        return $this->model;
    }

    public function setFilters()
    {

    }

    public function getMessages($elementName = null)
    {
        $elemenets = parent::getMessages($elementName = null);

        $translate = [
            "Invalid type given. String, integer or float expected" => "Неверный тип данных. Ожидается строка",
            "Invalid type given. String expected" => "Неверный тип данных. Ожидается строка",
            "The input is not a valid email address. Use the basic format local-part@hostname" => "Неверный e-mail адрес",
            "The input is less than %min% characters long" => "Значение должно быть больше %min% символов",
            "Value is required and can't be empty" => "Поле не заполнено",
            "A record matching the input was found" => "Такая запись уже есть",
            "The two given tokens do not match" => "Значения не совпадают",

            "'%hostname%' is not a valid hostname for the email address" => "'%hostname%' не может быть использован как хост почты",
            "The input does not match the expected structure for a DNS hostname" => "Неверный формат E-mail адреса",
            "The input appears to be a local network name but local network names are not allowed" => "Локальные почтовые адреса не разрешены",

            "The input does not match against pattern '%pattern%'" => "Значение не соответствует требованиям",
            "Allowed only Latin characters and digits" => "Разрешены только латинские символы и цифры",
        ];

        foreach ($elemenets as $name => &$errors) {
            foreach ($errors as $key => &$error) {
                if(!array_key_exists($error, $translate)) {
                    continue;
                }
                $error = $translate[$error];
            }
        }

        return $elemenets;
    }

    public function add($elementOrFieldset, array $flags = [])
    {
        if(!$this->translate) {
            return parent::add($elementOrFieldset, $flags);
        }

        if($elementOrFieldset['options']['label']) {
            $language = Translator::getInstance();
            $elementOrFieldset['options']['label'] = $language->translate($elementOrFieldset['options']['label']);
        }

        if($elementOrFieldset['attributes']['placeholder']) {
            $language = Translator::getInstance();
            $elementOrFieldset['attributes']['placeholder'] = $language->translate($elementOrFieldset['attributes']['placeholder']);
        }

        return parent::add($elementOrFieldset, $flags);
    }
}