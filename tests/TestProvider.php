<?php
namespace tests;

use icron\confirm\providers\IProvider;

class TestProvider implements IProvider
{
    public function send($destination, $code)
    {
        return true;
    }
}