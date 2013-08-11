<?php namespace McCool\Tests\Stubs;

class DecoratedAtom
{
    public $presenter = 'McCool\Tests\Stubs\DecoratedAtomPresenter';

    public function favoriteLanguage()
    {
        return 'C#';
    }
}