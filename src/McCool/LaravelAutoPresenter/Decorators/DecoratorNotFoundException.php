<?php namespace McCool\LaravelAutoPresenter\Decorators;

/**
* This exception is thrown when no matching decorators can be found to handle a subject.
*/
class DecoratorNotFoundException extends \Exception
{
	public function __construct($subject)
	{
		$this->message = 'No decorator could be found for object of type ['.class_name($subject).'].';
	}
}
