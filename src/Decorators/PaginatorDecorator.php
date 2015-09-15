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
use McCool\LaravelAutoPresenter\AutoPresenter;
use ReflectionObject;

class PaginatorDecorator implements DecoratorInterface
{
    /**
     * The auto presenter instance.
     *
     * @var \McCool\LaravelAutoPresenter\AutoPresenter
     */
    protected $autoPresenter;

    /**
     * Create a new paginator decorator.
     *
     * @param \McCool\LaravelAutoPresenter\AutoPresenter $autoPresenter
     *
     * @return void
     */
    public function __construct(AutoPresenter $autoPresenter)
    {
        $this->autoPresenter = $autoPresenter;
    }

    /**
     * Can the subject be decorated?
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
     * Decorate a given subject.
     *
     * @param object $subject
     *
     * @return object
     */
    public function decorate($subject)
    {
        $items = $this->getItems($subject);

        foreach ($items->keys() as $key) {
            $items->put($key, $this->autoPresenter->decorate($items->get($key)));
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

    /**
     * Get the auto presenter instance.
     *
     * @codeCoverageIgnore
     *
     * @return \McCool\LaravelAutoPresenter\AutoPresenter
     */
    public function getAutoPresenter()
    {
        return $this->autoPresenter;
    }
}
