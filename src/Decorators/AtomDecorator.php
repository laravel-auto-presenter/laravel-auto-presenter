<?php

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace McCool\LaravelAutoPresenter\Decorators;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use McCool\LaravelAutoPresenter\Exceptions\PresenterNotFound;
use McCool\LaravelAutoPresenter\HasPresenter;

class AtomDecorator extends BaseDecorator implements DecoratorInterface
{
    /**
     * Can the subject be decorated?
     *
     * If the subject is an eloquent model, or implements has presenter, then
     * it can be decorated.
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
     * @throws \McCool\LaravelAutoPresenter\Exceptions\PresenterNotFound
     *
     * @return object
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

        return $this->container->make($presenterClass, ['resource' => $subject]);
    }

    /**
     * Decorate the relationships of an Eloquent object.
     *
     * @param \Illuminate\Database\Eloquent\Model $atom
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function decorateRelations(Model $atom)
    {
        foreach ($atom->getRelations() as $relationName => $model) {
            if ($model instanceof Collection) {
                $model = $this->createDecorator('Collection')->decorate($model);
                $atom->setRelation($relationName, $model);
            } else {
                if ($model instanceof HasPresenter) {
                    $atom->setRelation($relationName, $this->decorate($model));
                }
            }
        }

        return $atom;
    }
}
