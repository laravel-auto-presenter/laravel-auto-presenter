<?php

namespace McCool\LaravelAutoPresenter\Exceptions;

class DecoratorNotFound extends NotFound
{
    /**
     * Create a new decorator not found exception.
     *
     * @param string      $class
     * @param string|null $message
     *
     * @return void
     */
    public function __construct($class, $message = null)
    {
        if (!$message) {
            $message = "The decorator class '$class' was not found.";
        }

        parent::__construct($class, $message);
    }
}
