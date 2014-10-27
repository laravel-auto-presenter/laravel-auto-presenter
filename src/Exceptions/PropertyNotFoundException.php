<?php namespace McCool\LaravelAutoPresenter\Exceptions;

class PropertyNotFoundException extends NotFoundException
{
    /**
     * The requested property.
     *
     * @var string
     */
    protected $property;

    /**
     * Create a new property not found exception.
     *
     * @param string      $class
     * @param string      $property
     * @param string|null $message
     *
     * @return void
     */
    public function __construct($class, $property, $message = null)
    {
        $this->property = $property;

        if (!$message) {
            $message = "The property '$property' was not found on the presenter class '$class'.";
        }

        parent::__construct($class, $message);
    }

    /**
     * Get the requested property.
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }
}
