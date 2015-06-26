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

use McCool\LaravelAutoPresenter\Decorators\DecoratorInterface;

class AutoPresenter
{
    /**
     * The registered decorators.
     *
     * @var \McCool\LaravelAutoPresenter\Decorators\DecoratorInterface[]
     */
    protected $decorators = [];

    /**
     * Create a new auto presenter.
     *
     * @param \McCool\LaravelAutoPresenter\Decorators\DecoratorInterface[] $decorators
     *
     * @return void
     */
    public function __construct(array $decorators = [])
    {
        $this->decorators = $decorators;
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

    /**
     * Register a decorator.
     *
     * @param \McCool\LaravelAutoPresenter\Decorators\DecoratorInterface $decorator
     *
     * @return void
     */
    public function register(DecoratorInterface $decorator)
    {
        $this->decorators[] = $decorator;
    }

    /**
     * Get the registered decorators.
     *
     * @return \McCool\LaravelAutoPresenter\Decorators\DecoratorInterface[]
     */
    public function getDecorators()
    {
        return $this->decorators;
    }
}
