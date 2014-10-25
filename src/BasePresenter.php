<?php namespace McCool\LaravelAutoPresenter;

abstract class BasePresenter
{
    /**
     * The resource that is the object that was decorated.
     */
    private $wrappedObject = null;

    /**
     * Construct the presenter and provide the resource that the presenter will
     * represent.
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
     * Public resource getter.
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
     * @return mixed
     */
    public function __get($key)
    {
        if (property_exists($this->wrappedObject, $key) || isset($this->wrappedObject->$key)) {
            return $this->wrappedObject->$key;
        }

        return $this->{$key}();
    }

    /**
     * Magic Method access for methods called against the presenter looks for a
     * method on the resource, or throws an exception if none is found.
     *
     * @param string $key
     * @param array  $args
     *
     * @throws MethodNotFound
     *
     * @return mixed
     */
    public function __call($key, $args)
    {
        if (method_exists($this->wrappedObject, $key)) {
            return call_user_func_array(array($this->wrappedObject, $key), $args);
        }

        throw new MethodNotFound('Presenter: '.get_called_class().'::'.$key.' method does not exist');
    }

    /**
     * Magic Method isset access measures the existence of a property on the
     * resource using ArrayAccess.
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
     * Magic Method toString is deferred to the resource.
     */
    public function __toString()
    {
        return (string) $this->wrappedObject;
    }
}
