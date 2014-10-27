<?php namespace McCool\Tests\Facades;

use GrahamCampbell\TestBench\Traits\FacadeTestCaseTrait;
use McCool\Tests\AbstractTestCase;

class AutoPresenterTest extends AbstractTestCase
{
    use FacadeTestCaseTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'autopresenter';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return 'McCool\LaravelAutoPresenter\Facades\AutoPresenter';
    }

    /**
     * Get the facade route.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return 'McCool\LaravelAutoPresenter\PresenterDecorator';
    }
}
