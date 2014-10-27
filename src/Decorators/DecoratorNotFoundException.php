<?php namespace McCool\LaravelAutoPresenter\Decorators;

class DecoratorNotFoundException extends \Exception
{
    public function __construct($decorator)
    {
        $this->message = 'No decorator could be found for object of type ['.$decorator.'].';
    }
}
