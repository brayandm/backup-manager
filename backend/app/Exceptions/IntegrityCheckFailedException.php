<?php

namespace App\Exceptions;

use Exception;

class IntegrityCheckFailedException extends Exception
{
    protected $statusCode;

    public function __construct($message = "Integrity Check Failed", $statusCode = 422)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'Integrity Check Failed',
            'message' => $this->getMessage(),
        ], $this->statusCode);
    }
}

