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

use Exception;

class NotFoundException extends Exception
{
    /**
     * The class name.
     *
     * @var string
     */
    protected $class;

    /**
     * Create a new not found exception.
     *
     * @param string $class
     * @param string $message
     *
     * @return void
     */
    public function __construct($class, $message)
    {
        $this->class = $class;

        parent::__construct($message);
    }

    /**
     * Get the class name.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}
