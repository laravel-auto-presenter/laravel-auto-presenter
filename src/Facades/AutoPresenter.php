<?php namespace McCool\LaravelAutoPresenter\Facades;

use Illuminate\Support\Facades\Facade;

class AutoPresenter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'autopresenter';
    }
}
