<?php namespace McCool\LaravelAutoPresenter;

use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\Decorators\CollectionDecorator;
use McCool\LaravelAutoPresenter\Decorators\DecoratorNotFoundException;
use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;

/**
 * The class that decorates model objects, paginators and collections.
 */
class PresenterDecorator
{
    /**
     * Setup the presenter decorator with the possible decorators to be used for subjects.
     *
     * @param AtomDecorator       $atomDecorator
     * @param CollectionDecorator $collectionDecorator
     * @param PaginatorDecorator  $paginationDecorator
     *
     * @return void
     */
    public function __construct(
        AtomDecorator $atomDecorator,
        CollectionDecorator $collectionDecorator,
        PaginatorDecorator $paginationDecorator
    ) {
        $this->decorators[] = $atomDecorator;
        $this->decorators[] = $collectionDecorator;
        $this->decorators[] = $paginationDecorator;
    }

    /**
     * Things go in, get decorated (or not) and are returned.
     *
     * @param mixed $subject
     *
     * @throws DecoratorNotFoundException
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
