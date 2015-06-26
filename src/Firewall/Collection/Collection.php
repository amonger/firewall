<?php

namespace amonger\Firewall\Collection;

use ArrayAccess;

class Collection implements ArrayAccess
{
    private $container = array();

    public function __construct()
    {
        $this->container = array();
    }

    public function count()
    {
        return count($this->container);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}