<?php
namespace HotelsAdmin\Model;

use Aptero\Db\Entity\Entity;
use Aptero\Db\Entity\EntityCollection;
use Aptero\Db\Plugin\Images;
use Sync\Model\Sync;
use TranslatorAdmin\Model\Translator;
use Zend\Db\Sql\Expression;

class Hotel extends Entity
{
    public function __construct()
    {
        $this->setTable('hotels');

        $this->addProperties([
            'type'              => [],
        ]);

        //$this->addTranslate($this);
    }

    public function getPublicUrl()
    {
        return '/hotels/' . $this->get('url') . '/';
    }

    public function getUrl()
    {
        return '/admin/hotels/hotels/edit/?id=' . $this->getId();
    }
}