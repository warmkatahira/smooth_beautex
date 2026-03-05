<?php

namespace App\Exceptions;

use Exception;

class ShippingWorkEndException extends Exception
{
    protected $target_count;
    protected $is_successful;

    public function __construct($message, $target_count = null, $is_successful = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->target_count = $target_count;
        $this->is_successful = $is_successful;
    }

    public function getTargetCount()
    {
        return $this->target_count;
    }

    public function getIsSuccessful()
    {
        return $this->is_successful;
    }
}