<?php

namespace McCool\LaravelAutoPresenter\Decorators;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use McCool\LaravelAutoPresenter\PresenterNotFound;

class AtomDecorator extends BaseDecorator implements DecoratorInterface
{
    /**
     * The only valid $subject for this decorator, is one of a Collection
     * instance.
     *
     * @param mixed $subject
     *
     * @return bool
     */
    public function canDecorate($subject)
    {
        return ($subject instanceof HasPresenter || $subject instanceof Model);
    }

    /**
     * Decorate a collection instance.
     *
     * @param object $subject
     *
     * @throws PresenterNotFound
     *
     * @return mixed
     */
    public function decorate($subject)
    {
        if ($subject instanceof Model) {
            $subject = $this->decorateRelations($subject);
        }

        if (!$subject instanceof HasPresenter) {
            return $subject;
        }

        $presenterClass = $subject->getPresenterClass();

        if (!class_exists($presenterClass)) {
            throw new PresenterNotFound($presenterClass);
        }

        return new $presenterClass($subject);
    }

    /**
     * Decorate the relationships of an Eloquent object.
     *
     * @param \Illuminate\Database\Eloquent\Model $atom
     *
     * @return mixed
     */
    protected function decorateRelations(Model $atom)
    {
        foreach ($atom->getRelations() as $relationName => $model) {
            if ($model instanceof Collection) {
                $model = $this->createDecorator('Collection')->decorate($model);
                $atom->setRelation($relationName, $model);
            } else {
                $atom->setRelation($relationName, $this->decorate($model));
            }
        }

        return $atom;
    }
}
