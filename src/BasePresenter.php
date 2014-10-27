<?php namespace McCool\LaravelAutoPresenter;

use McCool\LaravelAutoPresenter\Exceptions\MethodNotFoundException;
use McCool\LaravelAutoPresenter\Exceptions\PropertyNotFoundException;

abstract class BasePresenter
{
    /**
     * The resource that is the object that was decorated.
     *
     * @var object|null
     */
    protected $wrappedObject = null;

    /**
     * Create a new presenter.
     *
     * @param object $resource
     *
     * @return void
     */
    public function __construct($resource)
    {
        $this->wrappedObject = $resource;
    }

    /**
     * Get the wrapped object.
     *
     * @return mixed
     */
    public function getWrappedObject()
    {
        return $this->wrappedObject;
    }

    /**
     * Magic method access initially tries for local fields, then defers to the
     * decorated object.
     *
     * @param string $key
     *
     * @throws \McCool\LaravelAutoPresenter\Exceptions\PropertyNotFoundException
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->{$key}();
        }

        if (property_exists($this->wrappedObject, $key) || isset($this->wrappedObject->$key)) {
            return $this->wrappedObject->$key;
        }

        throw new PropertyNotFoundException(get_called_class(), $key);
    }

    /**
     * Magic Method access for methods called against the presenter looks for a
     * method on the resource, or throws an exception if none is found.
     *
     * @param string $key
     * @param array  $args
     *
     * @throws \McCool\LaravelAutoPresenter\Exceptions\MethodNotFoundException
     *
     * @return mixed
     */
    public function __call($key, $args)
    {
        if (method_exists($this->wrappedObject, $key)) {
            return call_user_func_array(array($this->wrappedObject, $key), $args);
        }

        throw new MethodNotFoundException(get_called_class(), $key);
    }

    /**
     * Is the key set on the wrapped object?
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->wrappedObject[$key]);
    }

    /**
     * Get the wrapped object, cast to a string.
     */
    public function __toString()
    {
        return (string) $this->wrappedObject;
    }
}
