<?php

namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\HasPresenter;

class WronglyDecoratedAtom implements HasPresenter
{
    public function getPresenterClass()
    {
        return 'ThisClassDoesntExistAnywhereInTheKnownUniverse';
    }
}
