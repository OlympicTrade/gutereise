<?php
namespace Aptero\Db\Entity\Filters;

use \Zend\Json\Json as ZJson;

class Json extends AbstractFilter
{
    /**
     * @var \stdClass null
     */
    protected $value = null;

    public function __construct()
    {
        $this->setSource('');
    }

    public function set($value)
    {
        if(is_array($value)) {
            $this->value = ZJson::decode(ZJson::encode($value));
        } elseif($value instanceof \StdClass) {
            $this->value = $value;
        } else {
            $this->setSource($value);
        }

        $this->isChanged(true);
        return $this;
    }

    public function get()
    {
        $this->isChanged(true);
        if($this->value) return $this->value;

        return $this->unserialize();
    }

    public function unserialize()
    {
        try {
            $this->value = ZJson::decode($this->source);
        } catch (\Exception $e) {
            $this->value = new \stdClass();
        }

        return $this->value;
    }

    public function serialize()
    {
        $this->source = $this->value ? ZJson::encode($this->value) : '';

        return $this->source;
    }

    public function unserializeArray($arr, &$param = false)
    {
        foreach ($arr as $key => $val) {
            if(!is_array($val)) {
                @$param->{$key} = $val;
                continue;
            }
            $this->unserializeArray($val, $param->{$key});
        }
    }

    public function serializeArray($result = [], $prefix = '')
    {
        $this->unserialize();
        $result = $this->serializeHelper($this->value, $result, $prefix);

        return $result;
    }

    public function serializeHelper($obj, $result = [], $prefix = '')
    {
        foreach ($obj as $key => $val) {
            if(is_object($val)) {
                $result = $this->serializeHelper($val, $result, $prefix . '[' . $key . ']');
            } else {
                $result[$prefix . '[' . $key . ']'] = $val;
            }
        }

        return $result;
    }

    public function setSource($value)
    {
        parent::setSource($value);
        $this->unserialize();

        return $this;
    }
}