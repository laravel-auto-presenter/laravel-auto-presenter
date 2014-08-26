<?php namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\BasePresenter;

class DecoratedAtomPresenter extends BasePresenter
{
    public function favoriteMovie()
    {
        return 'Primer';
    }
}
