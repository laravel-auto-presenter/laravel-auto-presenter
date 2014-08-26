<?php namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\HasPresenter;

class DecoratedAtom implements HasPresenter
{
    public function favoriteLanguage()
    {
        return 'C#';
    }

	public function toArray()
	{
		return ['name' => 'Me', 'address' => 'Somewhere, over there!', 'random' => 'field value'];
	}

    /**
     * Get the presenter class.
     * @return string The class path to the presenter.
     */
    public function getPresenterClass()
    {
        return DecoratedAtomPresenter::class;
    }
}
