<?php
namespace tests;

use yii\base\Component;

class Session extends Component
{
    private $_data;

    public function get($key, $defaultValue = null)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : $defaultValue;
    }

    public function set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    public function getData()
    {
        return $this->_data;
    }
}
