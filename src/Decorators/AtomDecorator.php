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

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\AutoPresenter;
use McCool\LaravelAutoPresenter\Exceptions\PresenterNotFoundException;
use McCool\LaravelAutoPresenter\HasPresenter;

class AtomDecorator implements DecoratorInterface
{
    /**
     * The auto presenter instance.
     *
     * @var \McCool\LaravelAutoPresenter\AutoPresenter
     */
    protected $autoPresenter;

    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Create a new atom decorator.
     *
     * @param \McCool\LaravelAutoPresenter\AutoPresenter $autoPresenter
     * @param \Illuminate\Contracts\Container\Container  $container
     *
     * @return void
     */
    public function __construct(AutoPresenter $autoPresenter, Container $container)
    {
        $this->autoPresenter = $autoPresenter;
        $this->container = $container;
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
        return $subject instanceof HasPresenter;
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
        if (is_object($subject)) {
            $subject = clone $subject;
        }

        if ($subject instanceof Model) {
            foreach ($subject->getRelations() as $relationName => $model) {
                $subject->setRelation($relationName, $this->autoPresenter->decorate($model));
            }
        }

        if (!class_exists($presenterClass = $subject->getPresenterClass())) {
            throw new PresenterNotFoundException($presenterClass);
        }

        return $this->container->make($presenterClass, ['resource' => $subject]);
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

    /**
     * Get the container instance.
     *
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Contracts\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
