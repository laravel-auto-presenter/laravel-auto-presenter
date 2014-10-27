<?php

namespace McCool\Tests\Decorators;

use Illuminate\Pagination\Paginator;
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;
use McCool\Tests\TestCase;
use Mockery as m;

class PaginatorDecoratorTest extends TestCase
{
    private $paginatorDecorator;

    public function setUp()
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
        $paginator = new Paginator(['an item'], 2);

        $this->paginatorDecorator->getContainer()->shouldReceive('make')->once()
            ->with(AtomDecorator::class)->andReturn(new AtomDecorator($this->paginatorDecorator->getContainer()));

        $this->paginatorDecorator->decorate($paginator);
    }
}
