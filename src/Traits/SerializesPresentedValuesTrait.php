<?php

namespace McCool\LaravelAutoPresenter\Traits;

use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * Class SerializesPresentedValuesTrait
 * @package McCool\LaravelAutoPresenter\Traits
 *
 * @property $presented array an array of property names to include when serialized
 *
 * Trait should be applied to the Model being decorated. Works with the ArrayAccessTrait on presenters to be able
 * to access decorated values once the model has been serialized (like within queued events).
 */
trait SerializesPresentedValuesTrait
{

	/**
	 * Intercept calls to the models toArray function to inject presented values
	 *
	 * @return array
	 */
	public function toArray()
	{
		$model = parent::toArray();

		if($this instanceof HasPresenter && count($this->presented)){
			$presenter_class = $this->getPresenterClass();
			$presenter = new $presenter_class($this);

			$presented = [];
			foreach ($this->presented as $key) {
				$presented[$key] = $presenter->{$key}();
			}

			$all = array_merge($model, $presented);

			return $all;
		} else {
			return $model;
		}
	}
}