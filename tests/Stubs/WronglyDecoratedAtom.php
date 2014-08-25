<?php namespace McCool\Tests\Stubs;

class WronglyDecoratedAtom
{
    public function getPresenter()
    {
    	return 'ThisClassDoesntExistAnywhereInTheKnownUniverse';
    }
}
