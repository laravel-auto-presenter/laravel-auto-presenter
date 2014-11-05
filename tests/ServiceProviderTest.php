<?php

namespace McCool\Tests;

use GrahamCampbell\TestBench\Traits\ServiceProviderTestCaseTrait;

class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTestCaseTrait;

    public function testAtomDecoratorIsInjectable()
    {
        $this->assertIsInjectable('McCool\LaravelAutoPresenter\Decorators\AtomDecorator');
    }

    public function testCollectionDecoratorIsInjectable()
    {
        $this->assertIsInjectable('McCool\LaravelAutoPresenter\Decorators\CollectionDecorator');
    }

    public function testPaginatorDecoratorIsInjectable()
    {
        $this->assertIsInjectable('McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator');
    }

    public function testPresenterDecoratorIsInjectable()
    {
        $this->assertIsInjectable('McCool\LaravelAutoPresenter\PresenterDecorator');
    }
}
