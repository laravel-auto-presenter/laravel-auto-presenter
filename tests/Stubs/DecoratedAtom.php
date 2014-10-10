<?php namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\HasPresenter;

class DecoratedAtom implements HasPresenter
{
    public $myProperty = "bazinga";

    /**
     * Get the presenter class.
     * @return string The class path to the presenter.
     */
    public function getPresenterClass()
    {
        return DecoratedAtomPresenter::class;
    }
}
