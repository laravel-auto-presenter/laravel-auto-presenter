<?php namespace McCool\LaravelAutoPresenter;

use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\Decorators\CollectionDecorator;
use McCool\LaravelAutoPresenter\Decorators\DecoratorNotFoundException;
use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;

class PresenterDecorator
{
    /**
     * Create a new presenter decorator.
     *
     * This is the class that decorates models, paginators and collections.
     *
     * @param \McCool\LaravelAutoPresenter\Decorators\AtomDecorator       $atom
     * @param \McCool\LaravelAutoPresenter\Decorators\CollectionDecorator $collection
     * @param \McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator  $pagination
     *
     * @return void
     */
    public function __construct(AtomDecorator $atom, CollectionDecorator $collection, PaginatorDecorator $pagination)
    {
        $this->decorators[] = $atom;
        $this->decorators[] = $collection;
        $this->decorators[] = $pagination;
    }

    /**
     * Things go in, get decorated (or not) and are returned.
     *
     * @param mixed $subject
     *
     * @throws \McCool\LaravelAutoPresenter\Decorators\DecoratorNotFoundException
     *
     * @return mixed
     */
    public function decorate($subject)
    {
        foreach ($this->decorators as $possibleDecorator) {
            if ($possibleDecorator->canDecorate($subject)) {
                return $possibleDecorator->decorate($subject);
            }
        }

        return $subject;
    }
}
