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

class CollectionDecorator extends BaseDecorator implements DecoratorInterface
{
    /**
     * Can the subject be decorated?
     *
     * If the subject is a collection, then it can be decorated.
     *
     * @param mixed $subject
     *
     * @return bool
     */
    public function canDecorate($subject)
    {
        return $subject instanceof Collection;
    }

    /**
     * Decorate a collection instance.
     *
     * @param \Illuminate\Support\Collection $subject
     *
     * @return \Illuminate\Support\Collection
     */
    public function decorate($subject)
    {
        foreach ($subject as $key => $atom) {
            if (is_array($atom)) {
                $subject[$key] = $this->decorate($atom);
            } elseif ($this->canDecorate($atom)) {
                $subject[$key] = $this->decorate($atom);
            } else {
                $subject[$key] = $this->createDecorator('Atom')->decorate($atom);
            }
        }

        return $subject;
    }
}
