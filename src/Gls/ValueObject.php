<?php

namespace Plab\GlsUniboxDelivery\Gls;


abstract class ValueObject
{
    public function __get($key)
    {
        if (!property_exists($this, $key)) {
            throw new \Exception('This property non exist');
        }

        return $this->$key;
    }

    public function __set($key, $value)
    {
        throw new \Exception('In ValueObject you are not allowed to setup property');
    }
}