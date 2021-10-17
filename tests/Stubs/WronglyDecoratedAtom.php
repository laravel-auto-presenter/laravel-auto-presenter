<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\HasPresenter;

class WronglyDecoratedAtom implements HasPresenter
{
    public function getPresenterClass()
    {
        return 'ThisClassDoesntExistAnywhereInTheKnownUniverse';
    }
}
