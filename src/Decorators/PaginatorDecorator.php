<?php

namespace McCool\LaravelAutoPresenter\Decorators;

use Illuminate\Contracts\Pagination\Paginator;
use ReflectionObject;

class PaginatorDecorator extends BaseDecorator implements DecoratorInterface
{
    /**
     * Can the subject be decorated?
     *
     * If the subject is a paginator, then it can be decorated.
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
        $items = $this->getItems($subject);

        foreach ($items->keys() as $key) {
            $item = $items->get($key);
            $decorated = $this->createDecorator('Atom')->decorate($item);
            $items->put($key, $decorated);
        }

        return $subject;
    }

    protected function getItems($subject)
    {
        $object = new ReflectionObject($subject);

        $items = $object->getProperty('items');
        $items->setAccessible(true);

        return $items->getValue($subject);
    }
}
