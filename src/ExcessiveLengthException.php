<?php

namespace DevToolsGuru\Password;

use \Exception;

class ExcessiveLengthException extends Exception
{
    protected $message = 'A password was provided that exceeded the maximum length available to the default hashing algorithm.';

    protected $maxLength;

    public function setMaxLength(int $length)
    {
        $this->maxLength = $length;
    }
}