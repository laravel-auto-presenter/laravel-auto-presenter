<?php namespace McCool\LaravelAutoPresenter\Decorators;

/**
 * This exception is thrown when no matching decorators can't be found to handle a subject.
 */
class DecoratorNotFoundException extends \Exception
{
    public function __construct($decorator)
    {
        $this->message = 'No decorator could be found for object of type ['.$decorator.'].';
    }
}
