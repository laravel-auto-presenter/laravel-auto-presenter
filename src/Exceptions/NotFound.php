<?php

namespace McCool\LaravelAutoPresenter\Exceptions;

use Exception;

class NotFound extends Exception
{
    /**
     * The class name.
     *
     * @var string
     */
    protected $class;

    /**
     * Create a new not found exception.
     *
     * @param string $class
     * @param string $message
     *
     * @return void
     */
    public function __construct($class, $message)
    {
        $this->class = $class;

        parent::__construct($message);
    }

    /**
     * Get the class name.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}
