<?php

namespace App\Exceptions;

class ExceptionWithArray extends \Exception
{
    private $ARRAY_DATA;

    public function __construct(
        $message,
        $array_data = array('params'),
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->ARRAY_DATA = $array_data;
    }

    public function getArrayData()
    {
        return $this->ARRAY_DATA;
    }
}
