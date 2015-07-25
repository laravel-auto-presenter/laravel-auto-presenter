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

use ArrayAccess;
use McCool\LaravelAutoPresenter\BasePresenter;
use McCool\LaravelAutoPresenter\Traits\ArrayAccessTrait;

class ModelPresenterArrayAccess extends BasePresenter implements ArrayAccess
{
	use ArrayAccessTrait;

    public function foo()
    {
        return $this->getWrappedObject()->foo.' there';
    }
}
