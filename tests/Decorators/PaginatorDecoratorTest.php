<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace McCool\Tests\Decorators;

use GrahamCampbell\TestBench\AbstractTestCase;
use Illuminate\Pagination\Paginator;
use McCool\LaravelAutoPresenter\AutoPresenter;
use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;
use Mockery;

class PaginatorDecoratorTest extends AbstractTestCase
{
    private $decorator;

    /**
     * @before
     */
    public function setUpProperties()
    {
        $this->decorator = new PaginatorDecorator(Mockery::mock(AutoPresenter::class));
    }

    public function testCanDecoratePaginator()
    {
        $this->assertTrue($this->decorator->canDecorate(Mockery::mock(Paginator::class)));
    }

    public function testCannotDecorateGarbage()
    {
        $this->assertFalse($this->decorator->canDecorate([]));
        $this->assertFalse($this->decorator->canDecorate(null));
        $this->assertFalse($this->decorator->canDecorate('garbage stuff yo'));
    }

    public function testDecorationOfPaginator()
    {
        Paginator::currentPageResolver(function () {
            // do nothing
        });

        $paginator = new Paginator(['an item'], 2);

        $this->decorator->getAutoPresenter()->shouldReceive('decorate')->once()->with('an item');

        $this->decorator->decorate($paginator);
    }
}
