<?php

namespace McCool\LaravelAutoPresenter\Decorators;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\PresenterInterface;
use McCool\LaravelAutoPresenter\PresenterNotFoundException;

class AtomDecorator implements DecoratorInterface
{
	/**
	 * The only valid $subject for this decorator, is one of a Collection instance.
	 *
	 * @param mixed $subject
	 * @return bool
	 */
	public function canDecorate($subject)
	{
		return (
			$subject instanceof PresenterInterface or
			$subject instanceof Model
		);
	}

	/**
	 * Decorate a collection instance.
	 *
	 * @param Model $subject
	 * @return mixed
	 * @throws PresenterNotFoundException
	 */
	public function decorate($subject)
	{
		if ($subject instanceof Model) {
			$subject = $this->decorateRelations($subject);
		}

		if (!$subject instanceof PresenterInterface) {
			return $subject;
		}

		$presenterClass = $subject->getPresenter();

		if (!class_exists($presenterClass)) {
			throw new PresenterNotFoundException($presenterClass);
		}

		return new $presenterClass($subject);
	}

	/**
	 * decorate the relationships of an Eloquent object
	 *
	 * @param \Illuminate\Database\Eloquent\Model $atom
	 * @return mixed
	 */
	protected function decorateRelations(Model $atom)
	{
		foreach ($atom->getRelations() as $relationName => $model) {
			if ($model instanceOf Collection) {
				$model = $this->createDecorator('Collection')->decorate($model);
				$atom->setRelation($relationName, $model);
			} else {
				$atom->setRelation($relationName, $this->decorate($model));
			}
		}

		return $atom;
	}
}
