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
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use McCool\LaravelAutoPresenter\AutoPresenter;
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\HasPresenter;
use Mockery;

class AtomDecoratorTest extends AbstractTestCase
{
    private $decorator;

    /**
     * @before
     */
    public function setUpProperties()
    {
        $this->decorator = new AtomDecorator(Mockery::mock(AutoPresenter::class), Mockery::mock(Container::class));
    }

    public function testCanDecorate()
    {
        $this->assertTrue($this->decorator->canDecorate(Mockery::mock(Model::class)));
    }

    public function testCanDecoratePresenter()
    {
        $this->assertTrue($this->decorator->canDecorate(Mockery::mock(HasPresenter::class)));
    }

    public function testCannotDecorateGarbage()
    {
        $this->assertFalse($this->decorator->canDecorate([]));
        $this->assertFalse($this->decorator->canDecorate(null));
        $this->assertFalse($this->decorator->canDecorate('garbage stuff yo'));
    }

    public function testShouldHandleRelationsWhenSubjectIsAModel()
    {
        $model = Mockery::mock(Model::class);
        $relations = ['blah'];

        $this->decorator->getAutoPresenter()->shouldReceive('decorate')->once()
            ->with($relations[0])->andReturn('foo');

        $model->shouldReceive('getRelations')->andReturn($relations);
        $model->shouldReceive('setRelation')->with(0, 'foo');

        $this->decorator->decorate($model);
    }

    public function testShouldHandleRelationsWhenSubjectIsAModelWithACollection()
    {
        $model = Mockery::mock(Model::class);
        $relations = [Mockery::mock(Collection::class)->makePartial()];

        $this->decorator->getAutoPresenter()->shouldReceive('decorate')->once()
            ->with($relations[0])->andReturn('bar');

        $model->shouldReceive('getRelations')->andReturn($relations);
        $model->shouldReceive('setRelation')->with(0, 'bar');

        $this->decorator->decorate($model);
    }
}
