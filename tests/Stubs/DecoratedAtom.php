<?php namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\PresenterInterface;

class DecoratedAtom implements PresenterInterface
{
    public function favoriteLanguage()
    {
        return 'C#';
    }

	public function getPresenter()
	{
		return 'McCool\Tests\Stubs\DecoratedAtomPresenter';
	}
}