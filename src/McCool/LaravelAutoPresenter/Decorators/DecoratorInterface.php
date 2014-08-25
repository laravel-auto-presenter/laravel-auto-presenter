<?php

namespace McCool\LaravelAutoPresenter\Decorators;

interface DecoratorInterface
{
	/**
	 * Determines whether or not a decorator is able to decorate a given subject.
	 *
	 * @param $subject
	 * @return boolean
	 */
	public function canDecorate($subject);

	/**
	 * Decorate a given subject.
	 *
	 * @param $subject
	 * @return mixed
	 */
	public function decorate($subject);
}
