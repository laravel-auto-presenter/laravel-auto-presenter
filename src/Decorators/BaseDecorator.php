<?php

namespace McCool\LaravelAutoPresenter\Decorators;

use Illuminate\Contracts\Container\Container;
use McCool\LaravelAutoPresenter\Exceptions\DecoratorNotFoundException;

abstract class BaseDecorator
{
    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Create a new decorator instance.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Some decorators depend on others to complete their task.
     *
     * This is a helper method to easily create decorators that are available.
     *
     * @param string $class
     *
     * @throws \McCool\LaravelAutoPresenter\Exceptions\DecoratorNotFoundException
     *
     * @return object
     */
    public function createDecorator($class)
    {
        $decoratorClass = implode('\\', [__NAMESPACE__, $class.'Decorator']);

        if (!class_exists($decoratorClass)) {
            throw new DecoratorNotFoundException($decoratorClass);
        }

        return $this->container->make($decoratorClass);
    }

    /**
     * Get the container instance.
     *
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }
}
