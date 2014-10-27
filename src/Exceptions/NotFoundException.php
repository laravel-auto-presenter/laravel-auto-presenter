<?php namespace McCool\LaravelAutoPresenter\Exceptions;

use Exception;

class NotFoundException extends Exception
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
     * @param string      $class
     * @param string|null $message
     *
     * @return void
     */
    public function __construct($class, $message = null)
    {
        $this->class = $class;

        if (!$message) {
            $message = "The class '$class' was not found.";
        }

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
