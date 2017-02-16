<?php

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace McCool\LaravelAutoPresenter\Decorators;

use Illuminate\Support\Collection;
use McCool\LaravelAutoPresenter\AutoPresenter;

class ArrayDecorator implements DecoratorInterface
{
    /**
     * The auto presenter instance.
     *
     * @var \McCool\LaravelAutoPresenter\AutoPresenter
     */
    protected $autoPresenter;

    /**
     * Create a new array decorator.
     *
     * @param \McCool\LaravelAutoPresenter\AutoPresenter $autoPresenter
     *
     * @return void
     */
    public function __construct(AutoPresenter $autoPresenter)
    {
        $this->autoPresenter = $autoPresenter;
    }

    /**
     * Can the subject be decorated?
     *
     * @param mixed $subject
     *
     * @return bool
     */
    public function canDecorate($subject)
    {
        return (is_array($subject) || $subject instanceof Collection) && $this->isNotIgnoreClass($subject);
    }

    /**
     * Ignore a class from decorate.
     *
     * @param $subject
     *
     * @return bool
     */
    public function isNotIgnoreClass($subject)
    {
        foreach (config()->get('laravel-auto-presenter.ignore-class-decorate', []) as $class) {
            if ($subject instanceof $class) {
                return false;
            }
        }

        return true;
    }

    /**
     * Decorate a given subject.
     *
     * @param object $subject
     *
     * @return object
     */
    public function decorate($subject)
    {
        foreach ($subject as $key => $atom) {
            $subject[$key] = $this->autoPresenter->decorate($atom);
        }

        return $subject;
    }

    /**
     * Get the auto presenter instance.
     *
     * @codeCoverageIgnore
     *
     * @return \McCool\LaravelAutoPresenter\AutoPresenter
     */
    public function getAutoPresenter()
    {
        return $this->autoPresenter;
    }
}
