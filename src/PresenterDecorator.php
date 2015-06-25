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

namespace McCool\LaravelAutoPresenter;

use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\Decorators\CollectionDecorator;
use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;

class PresenterDecorator
{
    /**
     * The decorators.
     *
     * @var array
     */
    protected $decorators = [];

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
        $this->decorators['atom'] = $atom;
        $this->decorators['collection'] = $collection;
        $this->decorators['pagination'] = $pagination;
    }

    /**
     * Things go in, get decorated (or not) and are returned.
     *
     * @param mixed $subject
     *
     * @return mixed
     */
    public function decorate($subject)
    {
        foreach ($this->decorators as $decorator) {
            if ($decorator->canDecorate($subject)) {
                return $decorator->decorate($subject);
            }
        }

        return $subject;
    }
}
