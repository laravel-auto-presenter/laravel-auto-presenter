<?php namespace McCool\LaravelAutoPresenter;

class BasePresenter implements \ArrayAccess
{
    /**
    * The resource that is the object that was decorated.
    */
    public $resource = null;

    public function __construct($resource = null)
    {
        $this->resource = $resource;
    }

    /**
    * Public resource getter
    */
    public function getResource()
    {
        return $this->resource;
    }

    /**
    * Magic Method access initially tries for local methods then, defers to
    * the decorated object.
    */
    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->{$key}();
        }

        return $this->resource->$key;
    }

    /**
    * Magic Method access for methods called against the presenter looks for
    * a method on the resource, or throws an exception if none is found.
    */
    public function __call($key, $args)
    {
        if (method_exists($this->resource, $key)) {
            return call_user_func_array(array($this->resource, $key), $args);
        }

        throw new ResourceMethodNotFoundException('Presenter: '.get_called_class().'::'.$key.' method does not exist');
    }

    /**
    * Magic Method isset access measures the existence of a property on the
    * resource using ArrayAccess.
    */
    public function __isset($key)
    {
        return isset($this->resource[$key]);
    }

    /**
    * Magic Method toString is deferred to the resource.
    */
    public function __toString()
    {
        return $this->resource->__toString();
    }

    /**
    * ArrayAccess interface implementation;
    */
    public function offsetExists($key)
    {
        return isset($this->resource[$key]);
    }

    public function offsetGet($key)
    {
        return $this->resource[$key];
    }

    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->resource[] = $value;
        } else {
            $this->resource[$key] = $value;
        }
    }

    public function offsetUnset($key)
    {
        unset($this->resource[$key]);
    }
}