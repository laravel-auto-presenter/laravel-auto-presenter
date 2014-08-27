<?php

namespace McCool\LaravelAutoPresenter\Decorators;

interface DecoratorInterface
{
    /**
     * Determines whether or not a decorator is able to decorate a given
     * subject.
     *
     * @param mixed $subject
     *
     * @return bool
     */
    public function canDecorate($subject);

    /**
     * Decorate a given subject.
     *
     * @param mixed $subject
     *
     * @return mixed
     */
    public function decorate($subject);
}
