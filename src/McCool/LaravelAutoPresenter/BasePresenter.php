<?php namespace McCool\LaravelAutoPresenter;

use \Illuminate\Support\Contracts\ArrayableInterface;

class BasePresenter implements \ArrayAccess, \JsonSerializable, ArrayableInterface
{
	/**
	 * The resource that is the object that was decorated.
	 */
	public $resource = null;

	/**
	 * Fields to restrict the returned result by. Same for example, you may want password
	 * removed, or to add additional fields. If this array is left empty, it will not affect
	 * the presenter. The presenter takes a white list approach.
	 *
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Construct the presenter and provide the resource that the presenter will represent.
	 *
	 * @param null $resource
	 */
	public function __construct($resource = null)
	{
		$this->resource = $resource;
	}

	/**
	 * Public resource getter.
	 *
	 * @return mixed
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 * Returns the array of fields that the presenter wishes to actually provide.
	 *
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * Not all fields may be accessible, for a number of reasons. Any fields
	 * that are not accessible, simply will not be usable in a view.
	 *
	 * @param $key
	 * @return boolean
	 */
	public function accessible($key)
	{
		$fields = $this->getFields();

		if (!$fields) return true;

		return array_search($key, $fields) !== false;
	}

	/**
	 * Return the value for a given field.
	 *
	 * @param $key
	 * @return mixed
	 */
	public function getField($key)
	{
		if ($this->accessible($key))
		{
			if (method_exists($this, $key)) {
				return $this->{$key}();
			}

			return $this->resource->$key;
		}
	}

    /**
    * Magic Method access initially tries for local methods then, defers to
    * the decorated object.
     *
     * @param string $key
     * @return mixed
    */
    public function __get($key)
    {
        return $this->getField($key);
    }

    /**
     * Magic Method access for methods called against the presenter looks for
     * a method on the resource, or throws an exception if none is found.
     *
     * @param string $key
     * @param array $args
     * @throws ResourceMethodNotFoundException
     * @return mixed
     */
    public function __call($key, $args)
    {
        if ($this->accessible($key) && method_exists($this->resource, $key)) {
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
    * Convert the object into something JSON serializable.
    *
    * @return array
    */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
    * Get the instance as an array.
    *
    * @return array
    */
    public function toArray()
    {
        return $this->resource->toArray();
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
