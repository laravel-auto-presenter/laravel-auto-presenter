<?php

namespace McCool\Tests;

use GrahamCampbell\TestBench\AbstractPackageTestCase;

abstract class AbstractTestCase extends AbstractPackageTestCase;
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
