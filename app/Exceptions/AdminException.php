<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class AdminException extends Exception
{
    protected $params = [];

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null, array $params = [])
    {
        parent::__construct($message, $code, $previous);

        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }
}
