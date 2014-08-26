<?php

namespace McCool\LaravelAutoPresenter\Decorators;

use Illuminate\Support\Collection;

class CollectionDecorator extends BaseDecorator implements DecoratorInterface
{
	/**
	 * The only valid $subject for this decorator, is one of a Collection instance.
	 *
	 * @param mixed $subject
	 * @return bool
	 */
	public function canDecorate($subject)
	{
		return $subject instanceof Collection;
	}

	/**
	 * Decorate a collection instance.
	 *
	 * @param Collection $subject
	 * @return mixed
	 */
	public function decorate($subject)
	{
		foreach ($subject as $key => $atom) {
			$subject->put($key, $this->createDecorator('Atom')->decorate($atom));
		}

		return $subject;
	}
} 