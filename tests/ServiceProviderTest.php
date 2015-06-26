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

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\Decorators\CollectionDecorator;
use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;
use McCool\LaravelAutoPresenter\AutoPresenter;

class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testAutoPresenterIsInjectable()
    {
        $this->assertIsInjectable(AutoPresenter::class);
    }
}
