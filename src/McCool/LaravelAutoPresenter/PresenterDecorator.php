<?php namespace McCool\LaravelAutoPresenter;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
* The class that decorates model objects, paginators and collections.
*/
class PresenterDecorator
{
    /**
    * things go in, get decorated (or not) and are returned
    *
    * @param mixed $subject
    * @return mixed
    */
    public function decorate($subject) {
        if ($subject instanceOf Paginator) {
            return $this->decoratePaginator($subject);
        }

        if ($subject instanceOf Collection) {
            return $this->decorateCollection($subject);
        }

        return $this->decorateAtom($subject);
    }

    /**
    * decorate the objects within a paginator
    *
    * @param \Illuminate\Pagination\Paginator $paginator
    * @return \Illuminate\Pagination\Paginator
    */
    protected function decoratePaginator(Paginator $paginator)
    {
        $newItems = array();

        foreach ($paginator->getItems() as $atom) {
            $newItems[] = $this->decorateAtom($atom);
        }

        $paginator->setItems($newItems);

        return $paginator;
    }

    /**
    * decorate the objects within a collection
    *
    * @param \Illuminate\Support\Collection $collection
    * @return \Illuminate\Support\Collection
    */
    protected function decorateCollection(Collection $collection)
    {
        foreach ($collection as $key => $atom) {
            $collection->put($key, $this->decorateAtom($atom));
        }

        return $collection;
    }

    /**
    * decorate an individual class
    *
    * @param mixed $atom
    * @return mixed
    */
    protected function decorateAtom($atom)
    {
        if ( ! isset($atom->presenter)) {
            return $atom;
        }

        if ($atom instanceOf BasePresenter) {
            return $atom;
        }

        $presenterClass = $atom->presenter;

        if ( ! class_exists($presenterClass)) {
            throw new PresenterNotFoundException($presenterClass);
        }

        if ($atom instanceOf Model) {
            $atom = $this->decorateRelations($atom);
        }

        return new $presenterClass($atom);
    }

    /**
    * decorate the relationships of an Eloquent object
    *
    * @param \Illuminate\Database\Eloquent\Model $atom
    * @return mixed
    */
    protected function decorateRelations(Model $atom)
    {
        foreach ($atom->getRelations() as $relationName => $model) {
            if ($model instanceOf Collection) {
                $model = $this->decorateCollection($model);
                $atom->setRelation($relationName, $model);
            } else {
                $atom->setRelation($relationName, $this->decorateAtom($model));
            }
        }

        return $atom;
    }
}