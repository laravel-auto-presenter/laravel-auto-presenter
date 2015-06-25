<?php

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param \Illuminate\Contracts\Pagination\Paginator $subject
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
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

    /**
     * Decorate a paginator instance.
     *
     * We're using hacky reflection for now because there is no public getter.
     *
     * @param \Illuminate\Contracts\Pagination\Paginator $subject
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getItems(Paginator $subject)
    {
        $object = new ReflectionObject($subject);

        $items = $object->getProperty('items');
        $items->setAccessible(true);

        return $items->getValue($subject);
    }
}
