<?php

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\BasePresenter;

class ModelPresenter extends BasePresenter
{
    public function foo()
    {
        return $this->getWrappedObject()->foo.' there';
    }
}
