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

namespace McCool\Tests\Decorators;

use GrahamCampbell\TestBench\AbstractTestCase;
use Illuminate\Pagination\Paginator;
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;
use Mockery as m;

class PaginatorDecoratorTest extends AbstractTestCase
{
    private $paginatorDecorator;

    /**
     * @before
     */
    public function setUpProperties()
    {
        $container = m::mock('Illuminate\Contracts\Container\Container');
        $this->paginatorDecorator = new PaginatorDecorator($container);
    }

    public function testCanDecoratePaginator()
    {
        $paginator = m::mock('Illuminate\Contracts\Pagination\Paginator');

        $this->assertTrue($this->paginatorDecorator->canDecorate($paginator));
        $this->assertFalse($this->paginatorDecorator->canDecorate('garbage stuff yo'));
    }

    public function testDecorationOfPaginator()
    {
        $this->resetPaginator();

        $paginator = new Paginator(['an item'], 2);

        $this->paginatorDecorator->getContainer()->shouldReceive('make')->once()
            ->with(AtomDecorator::class)->andReturn(new AtomDecorator($this->paginatorDecorator->getContainer()));

        $this->paginatorDecorator->decorate($paginator);
    }

    protected function resetPaginator()
    {
        Paginator::currentPageResolver(function () {
            // do nothing
        });
    }
}
