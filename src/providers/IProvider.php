<?php
namespace icron\confirm\providers;

interface IProvider
{
    public function send($destination, $code);
}