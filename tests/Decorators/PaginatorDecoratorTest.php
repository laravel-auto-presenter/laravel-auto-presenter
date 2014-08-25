<?php

namespace McCool\Tests\Decorators;

use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;
use McCool\Tests\TestCase;
use Mockery as m;

class PaginatorDecoratorTest extends TestCase
{
	private $paginatorDecorator;

	public function setUp()
	{
		$this->paginatorDecorator = new PaginatorDecorator;
	}

	public function testCanDecoratePaginator()
	{
		$paginator = m::mock('Illuminate\Pagination\Paginator');

		$this->assertTrue($this->paginatorDecorator->canDecorate($paginator));
		$this->assertFalse($this->paginatorDecorator->canDecorate('garbage stuff yo'));
	}

	public function testDecorationOfPaginator()
	{
		$paginator = m::mock('Illuminate\Pagination\Paginator');

		$paginator->shouldReceive('getItems')->andReturn(['an item']);
		$paginator->shouldReceive('setItems')->with(['an item']);

		$this->paginatorDecorator->decorate($paginator);
	}
}
