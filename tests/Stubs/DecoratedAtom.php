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

namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\HasPresenter;

class DecoratedAtom implements HasPresenter
{
    public $myProperty = 'bazinga';

    public function getPresenterClass()
    {
        return DecoratedAtomPresenter::class;
    }

    public function __get($key)
    {
        if ($key == 'testProperty') {
            return 'woop';
        }

        return $this->$key;
    }

    public function __toString()
    {
        return 'hello there';
    }
}
