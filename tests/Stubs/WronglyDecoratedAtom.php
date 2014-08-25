<?php namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\PresenterInterface;

class WronglyDecoratedAtom implements PresenterInterface
{
    public function getPresenter()
    {
        return 'ThisClassDoesntExistAnywhereInTheKnownUniverse';
    }
}
