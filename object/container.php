<?php

class Container
{

    private $_data;

    public function __construct()
    {
        $this->_data = array();
    }

    public function hasParam($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return TRUE;
        }
            return FALSE;
    }

    public function setParam($name, $value) {
        $this->_data[$name] = $value;
        return TRUE;
    }

    public function getParam($name)
    {
        if (self::hasParam($name)) {
            return $this->_data[$name];
        }
        return NULL;
    }

    public function rmParam($name) {
        if (self::hasParam($name)) {
            unset($this->_data[$name]);
        }
        return TRUE;
    }

    public function getKeys() {
        return array_keys($this->_data);
    }
}
