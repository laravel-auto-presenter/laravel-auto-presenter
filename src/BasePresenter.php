<?php

declare(strict_types=1);

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

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Str;
use JsonSerializable;
use McCool\LaravelAutoPresenter\Exceptions\MethodNotFoundException;

abstract class BasePresenter implements UrlRoutable, JsonSerializable
{
    /**
     * The resource that is the object that was decorated.
     *
     * @var object
     */
    protected $wrappedObject;

    /**
     * Get the wrapped object.
     *
     * @param object $resource
     *
     * @return $this
     */
    public function setWrappedObject($resource)
    {
        $this->wrappedObject = $resource;

        return $this;
    }

    /**
     * Get the wrapped object.
     *
     * @return object
     */
    public function getWrappedObject()
    {
        return $this->wrappedObject;
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->wrappedObject->getRouteKey();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return $this->wrappedObject->getRouteKeyName();
    }

    /**
     * Retrieve model for route model binding.
     *
     * @param mixed       $routeKey
     * @param string|null $field
     *
     * @return mixed
     */
    public function resolveRouteBinding($routeKey, $field = null)
    {
        if (method_exists($this->wrappedObject, 'resolveRouteBinding') && is_callable([$this->wrappedObject, 'resolveRouteBinding'])) {
            return $this->wrappedObject->resolveRouteBinding($routeKey, $field);
        }

        return $this->wrappedObject->where($field ?? $this->wrappedObject->getRouteKeyName(), $routeKey)->first();
    }

    /**
     * Retrieve the child model for a bound value.
     *
     * @param string      $childType
     * @param mixed       $value
     * @param string|null $field
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        if (method_exists($this->wrappedObject, 'resolveChildRouteBinding') && is_callable([$this->wrappedObject, 'resolveChildRouteBinding'])) {
            return $this->wrappedObject->resolveChildRouteBinding($childType, $value, $field);
        }

        return $this->wrappedObject->{Str::plural($childType)}()->where($field, $value)->first();
    }

    /**
     * Magic method access initially tries for local fields, then defers to the
     * decorated object.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        if (method_exists($this, $key)) {
            return $this->{$key}();
        }

        return $this->wrappedObject->$key;
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
    public function __call(string $key, array $args)
    {
        if (method_exists($this->wrappedObject, $key)) {
            return call_user_func_array([$this->wrappedObject, $key], $args);
        }

        throw new MethodNotFoundException(get_called_class(), $key);
    }

    /**
     * Is the key set on either the presenter or the wrapped object?
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset(string $key)
    {
        if (method_exists($this, $key)) {
            return true;
        }

        return isset($this->wrappedObject->$key);
    }

    /**
     * Get the wrapped object, cast to a string.
     */
    public function __toString()
    {
        return (string) $this->wrappedObject;
    }

    /**
     * Ensures the presenter will keep its behavior when json_encode is called on it.
     *
     * @return false|mixed|string
     */
    public function jsonSerialize()
    {
        $attributes = [];

        foreach ($this->wrappedObject->getAttributes() as $attribute => $value) {
            $attributes[$attribute] = $this->$attribute;
        }

        return $attributes;
    }
}
