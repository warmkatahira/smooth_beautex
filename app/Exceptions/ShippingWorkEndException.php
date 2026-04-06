<?php

namespace App\Exceptions;

use Exception;

class ShippingWorkEndException extends Exception
{
    protected $target_count;
    protected $is_successful;
    protected $error_file_name;

    public function __construct($message, $target_count = null, $is_successful = null, $error_file_name = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->target_count = $target_count;
        $this->is_successful = $is_successful;
        $this->error_file_name = $error_file_name;
    }

    public function getTargetCount()
    {
        return $this->target_count;
    }

    public function getIsSuccessful()
    {
        return $this->is_successful;
    }

    public function getErrorFileName()
    {
        return $this->error_file_name;
    }
}