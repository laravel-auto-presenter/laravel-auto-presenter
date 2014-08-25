<?php

namespace McCool\Tests\Decorators;

use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\Tests\TestCase;
use Mockery as m;

class AtomDecoratorTest extends TestCase
{
	private $atomDecorator;

	public function setUp()
	{
		$this->atomDecorator = new AtomDecorator;
	}

	public function testCanDecorateModel()
	{
		$model = m::mock('Illuminate\Database\Eloquent\Model');

		$this->assertTrue($this->atomDecorator->canDecorate($model));
	}

	public function testCanDecoratePresenterInterface()
	{
		$subject = m::mock('McCool\LaravelAutoPresenter\PresenterInterface');

		$this->assertTrue($this->atomDecorator->canDecorate($subject));
	}

	public function testCannotDecorateGarbage()
	{
		$this->assertFalse($this->atomDecorator->canDecorate([]));
		$this->assertFalse($this->atomDecorator->canDecorate(null));
	}

	public function testShouldHandleRelationsWhenSubjectIsAModel()
	{
		$model = m::mock('Illuminate\Database\Eloquent\Model');
		$collection = ['blah'];

		$model->shouldReceive('getRelations')->andReturn($collection);
		$model->shouldReceive('setRelation')->with(0, $collection[0]);

		$this->atomDecorator->decorate($model);
	}

	public function testShouldHandleRelationsWhenSubjectIsAModelWithACollection()
	{
		$model = m::mock('Illuminate\Database\Eloquent\Model');
		$collection = m::mock('Illuminate\Support\Collection')->makePartial();

		$model->shouldReceive('getRelations')->andReturn($collection);

		$this->atomDecorator->decorate($model);
	}
}
