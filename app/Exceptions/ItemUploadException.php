<?php

namespace App\Exceptions;

use Exception;

class ItemUploadException extends Exception
{
    protected $validation_error;
    protected $nowDate;
    protected $item_upload_history;

    public function __construct($message, $validation_error = null, $nowDate = null, $item_upload_history = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->validation_error = $validation_error;
        $this->nowDate = $nowDate;
        $this->item_upload_history = $item_upload_history;
    }

    public function getValidationError()
    {
        return $this->validation_error;
    }

    public function getNowDate()
    {
        return $this->nowDate;
    }

    public function getItemUploadHistory()
    {
        return $this->item_upload_history;
    }
}