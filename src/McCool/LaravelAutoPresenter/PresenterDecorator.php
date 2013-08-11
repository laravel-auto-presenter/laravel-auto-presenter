<?php namespace McCool\LaravelAutoPresenter;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class PresenterDecorator
{
    public function decorate($subject) {
        if ($subject instanceOf Paginator) {
            return $this->decoratePaginator($subject);
        }

        if ($subject instanceOf Collection) {
            return $this->decorateCollection($subject);
        }

        return $this->decorateAtom($subject);
    }

    protected function decoratePaginator(Paginator $paginator)
    {
        $newItems = [];

        foreach ($paginator->getIterator() as $atom) {
            $newItems[] = $this->decorateAtom($atom);
        }

        $paginator = new Paginator(
            $paginator->getEnvironment(),
            $newItems,
            $paginator->getTotal(),
            $paginator->getPerPage()
        );

        return $paginator->setupPaginationContext();
    }

    protected function decorateCollection(Collection $collection)
    {
        foreach ($collection as $key => $atom) {
            $collection->put($key, $this->decorateAtom($atom));
        }

        return $collection;
    }

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
            throw new InvalidPresenterException($presenterClass);
        }

        $atom = $this->decorateRelations($atom);

        return new $presenterClass($atom);
    }

    protected function decorateRelations($atom)
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