<?php namespace McCool\Tests\Stubs;

class DecoratedAtom
{
    public function getPresenter()
    {
    	return 'McCool\Tests\Stubs\DecoratedAtomPresenter';
    }

    public function favoriteLanguage()
    {
        return 'C#';
    }
}
