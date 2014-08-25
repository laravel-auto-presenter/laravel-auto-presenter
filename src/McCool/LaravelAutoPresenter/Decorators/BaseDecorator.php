<?php

namespace McCool\LaravelAutoPresenter\Decorators;

abstract class BaseDecorator
{
	/**
	 * Some decorators depend on others to complete their task. This is a helper method
	 * to easily create decorators that are available.
	 *
	 * @param $class
	 * @return mixed
	 */
	public function createDecorator($class)
	{
		$decoratorClass = implode('\\', [__NAMESPACE__, $class.'Decorator']);

		return new $decoratorClass;
	}
} 