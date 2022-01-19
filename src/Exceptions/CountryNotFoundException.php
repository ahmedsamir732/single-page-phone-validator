<?php
namespace Phone\Exceptions;

class CountryNotFoundException extends \Exception
{
    public function __construct($message = 'Country Not Found')
    {
        $this->message = $message;
    }
}