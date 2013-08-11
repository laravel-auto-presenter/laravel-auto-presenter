<?php namespace McCool\Tests\Stubs;

class DecoratedAtomPresenter extends \McCool\LaravelAutoPresenter\BasePresenter
{
    public function favorite_movie()
    {
        return 'Primer';
    }
}