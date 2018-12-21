<?php
namespace Aptero\Db\Entity\Filters;

class AbstractFilter
{
    /**
     * @var string
     */
    protected $source = '';

    /**
     * @var bool
     */
    protected $isChanged = false;

    public function set($value)
    {
        $this->setSource($value);
        return $this;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->source;
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->setSource('');
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setSource($value)
    {
        if($value == $this->source) {
            return $this;
        }

        $this->isChanged = true;
        $this->source = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param null $changed
     * @return $this|bool
     */
    public function isChanged($changed = null)
    {
        if($changed === null) return $this->isChanged;

        $this->isChanged = $changed;

        return $this;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        $result = $this->source;

        return $result;
    }

    /**
     * @return array
     */
    public function serializeForForm($prefix)
    {
        return [$prefix => $this->source];
    }
}