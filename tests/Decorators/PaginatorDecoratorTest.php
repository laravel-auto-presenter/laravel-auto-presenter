<?php

namespace McCool\Tests\Decorators;

use Illuminate\Pagination\Paginator;
use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;
use McCool\Tests\TestCase;
use Mockery as m;

class PaginatorDecoratorTest extends TestCase
{
    private $paginatorDecorator;

    public function setUp()
    {
        $this->paginatorDecorator = new PaginatorDecorator();
    }

    public function testCanDecoratePaginator()
    {
        $paginator = m::mock('Illuminate\Pagination\Paginator');

        $this->assertTrue($this->paginatorDecorator->canDecorate($paginator));
        $this->assertFalse($this->paginatorDecorator->canDecorate('garbage stuff yo'));
    }

    public function testDecorationOfPaginator()
    {
        $paginator = new Paginator(['an item'], 2);

        $this->paginatorDecorator->decorate($paginator);
    }
}
