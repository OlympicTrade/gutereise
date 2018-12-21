<?php
namespace TranslatorAdmin\Model;

use Aptero\Db\Entity\Entity;

class Translator extends Entity
{
    public function __construct()
    {
        $this->setTable('translator');

        $this->addProperties([
            'url'    => [],
            'code'   => [],
            'ru'     => [],
            'en'     => [],
            'de'     => [],
        ]);

        $this->addPropertyFilterIn('ru', function ($model, $val) {
            if($val) {
                $model->set('code', self::getCode($val));
            }
            return $val;
        });

        $this->getEventManager()->attach(array(Entity::EVENT_POST_INSERT), function ($event) {
            $event->getTarget()->updateFromDuplicates();
            return true;
        });
    }

    public function updateFromDuplicates() {
        $translate = new self();
        $translate->select()
            ->where
                ->equalTo('code', $this->get('code'))
                ->notEqualTo('id', $this->getId());

        if($translate->load()) {
            $this->setVariables([
                'en'    => $translate->get('en'),
                'de'    => $translate->get('de'),
            ]);
        }
    }

    static public function getCode($str)
    {
        $str = str_replace('<br>', '', $str);
        $codeStr = preg_replace("/[^\w]+/u", '', mb_strtolower($str));
        return hash('sha1', $codeStr);
    }

    static public function removeTranslate($parentCode) {
        $translates = Translator::getEntityCollection();
        $translates->select()
            ->where(['url' => $parentCode]);

        $translates->remove();
    }

    static public function addToTranslate($strs, $parentUrl = '') {
        if(!$strs) return;
        $strs = array_unique((array) $strs);

        $oldTranslates = Translator::getEntityCollection();
        $oldTranslates->select()
            ->where(['url' => $parentUrl]);

        foreach ($oldTranslates as $translate) {
            $exists = false;
            foreach ($strs as $key => $str) {
                if(self::getCode($str) == $translate->get('code')) {
                    $exists = true;
                    unset($strs[$key]);
                }
            }
            if(!$exists) {
                $translate->remove();
            }
        }

        foreach ($strs as $str) {
            $translator = new self();
            $translator->setVariables([
                'url'    => $parentUrl,
                'ru'     => $str,
            ])->save();
        }
    }
}