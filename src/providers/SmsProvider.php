<?php
namespace icron\confirm\providers;

class SmsProvider implements IProvider
{
    public function send($destination, $code)
    {
        return true;
    }
}
