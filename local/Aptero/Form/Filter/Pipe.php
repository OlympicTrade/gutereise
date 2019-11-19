<?php
namespace Aptero\Form\Filter;

use Zend\Filter\AbstractFilter;

class Pipe extends AbstractFilter
{
    public function filter($data)
    {
        return $data;
    }
}