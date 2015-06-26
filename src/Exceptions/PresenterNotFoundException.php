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

namespace McCool\LaravelAutoPresenter\Exceptions;

class PresenterNotFoundException extends NotFoundException
{
    /**
     * Create a new presenter not found exception.
     *
     * @param string      $class
     * @param string|null $message
     *
     * @return void
     */
    public function __construct($class, $message = null)
    {
        if (!$message) {
            $message = "The presenter class '$class' was not found.";
        }

        parent::__construct($class, $message);
    }
}
