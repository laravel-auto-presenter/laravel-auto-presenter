<?php

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
