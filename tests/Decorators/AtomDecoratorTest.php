<?php

namespace McCool\Tests\Decorators;

use GrahamCampbell\TestBench\AbstractTestCase;
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\HasPresenter;
use Mockery as m;

class AtomDecoratorTest extends AbstractTestCase
{
    private $atomDecorator;

    protected function start()
    {
        $container = m::mock('Illuminate\Contracts\Container\Container');
        $this->atomDecorator = new AtomDecorator($container);
    }

    public function testCanDecorateModel()
    {
        $model = m::mock('Illuminate\Database\Eloquent\Model');
        $this->assertTrue($this->atomDecorator->canDecorate($model));
    }

    public function testCanDecoratePresenterInterface()
    {
        $subject = m::mock(HasPresenter::class);
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
