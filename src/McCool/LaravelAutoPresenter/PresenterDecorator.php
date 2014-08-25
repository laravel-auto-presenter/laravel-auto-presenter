<?php namespace McCool\LaravelAutoPresenter;

use McCool\LaravelAutoPresenter\Decorators\DecoratorNotFoundException;

/**
 * The class that decorates model objects, paginators and collections.
 */
class PresenterDecorator
{
	private $decorators = [
		'McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator',
		'McCool\LaravelAutoPresenter\Decorators\CollectionDecorator',
		'McCool\LaravelAutoPresenter\Decorators\AtomDecorator'
	];

    /**
     * things go in, get decorated (or not) and are returned
     *
     * @param mixed $subject
     * @return mixed
     * @throws DecoratorNotFoundException
     */
    public function decorate($subject)
    {
	    foreach ($this->decorators as $possibleDecorator) {
		    $decorator = new $possibleDecorator;

		    if ($decorator->canDecorate($subject)) {
			    return $decorator->decorate($subject);
		    }
	    }

	    return $subject;
    }
}
