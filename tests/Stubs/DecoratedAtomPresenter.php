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

namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\BasePresenter;

class DecoratedAtomPresenter extends BasePresenter
{
    public function favoriteMovie()
    {
        return 'Primer';
    }
}
