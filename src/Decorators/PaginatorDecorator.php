<?php

namespace McCool\LaravelAutoPresenter\Decorators;

use Illuminate\Pagination\Paginator;

class PaginatorDecorator extends BaseDecorator implements DecoratorInterface
{
    /**
     * The only valid $subject for this decorator, is one of a Paginator
     * instance.
     *
     * @param mixed $subject
     *
     * @return bool
     */
    public function canDecorate($subject)
    {
        return $subject instanceof Paginator;
    }

    /**
     * Decorate a paginator instance.
     *
     * @param Paginator $subject
     *
     * @return mixed
     */
    public function decorate($subject)
    {
        $newItems = array();

        foreach ($subject->getItems() as $atom) {
            $newItems[] = $this->createDecorator('Atom')->decorate($atom);
        }

        $subject->setItems($newItems);

        return $subject;
    }
}
