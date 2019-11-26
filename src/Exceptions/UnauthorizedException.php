<?php

namespace TestMonitor\TOPdesk\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
