<?php
namespace icron\confirm\providers;

class EmailProvider implements IProvider
{
    public function send($destination, $code)
    {
        return true;
    }
}