<?php namespace McCool\Tests;

use GrahamCampbell\TestBench\AbstractLaravelTestCase as TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * Get the service provider class.
     *
     * @return string
     */
    protected function getServiceProviderClass()
    {
        return 'McCool\LaravelAutoPresenter\LaravelAutoPresenterServiceProvider';
    }
}
