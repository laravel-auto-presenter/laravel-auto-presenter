<?php

namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\HasPresenter;

class DependencyDecoratedAtom implements HasPresenter
{
    public function getPresenterClass()
    {
        return DependencyDecoratedAtomPresenter::class;
    }
}
